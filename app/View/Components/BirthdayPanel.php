<?php

namespace App\View\Components;

use App\Helpers\DateHelper;
use Illuminate\View\Component;
use Illuminate\Support\Carbon;
use App\Models\Contact\Contact;
use Illuminate\Database\Eloquent\Collection;

class BirthdayPanel extends Component
{
    public \Illuminate\Support\Collection $groupedContacts;

    public bool $showBirthdays = true;

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $this->prepareBirthdayItems();

        return view('components.birthday-panel');
    }

    private function prepareBirthdayItems()
    {
        $this->groupedContacts = $this->getContacts()
            ->groupBy($this->getGrouping())
            ->sortBy($this->getSorting())
            ->keyBy($this->getKeyBy())
            ->map(fn ($contacts) => $contacts->sortBy(fn ($c) => $c->birthdate->date->day));
    }

    private function getContacts(): Collection
    {
        return Contact::query()
            ->whereHas('birthdate', fn ($query) => $query->where('is_age_based', 0))
            ->whereNull('deceased_special_date_id')
            ->with('birthdate')
            ->get();
    }

    public function contactAge(Contact $contact): string
    {
        $age = $contact->birthdate->getAge();
        $md = Carbon::parse(Carbon::today()->year .'-'.$contact->birthdate->date->format('m-d'));
        if ($md->gt(now()->startOfDay()) || $md->month != now()->month) {
            $age++;
            $gt = true;
        }

        if ($contact->birthdate->is_year_unknown) {
            $contactAge = '';
        } elseif(isset($gt) || $md->isToday()) {
            $contactAge = __('people.age_will_turn_exact', ['age' => $age]);
        } else {
            $contactAge = __('people.age_turned_exact', ['age' => $age]);
        }

        return $contactAge;
    }

    private function getSorting(): \Closure
    {
        return fn ($items) => DateHelper::getMonthAndYearWithFormat(
            $items->first()->birthdate->date->month,
            'Ym'
        );
    }

    private function getGrouping(): \Closure
    {
        return fn ($contact) => $contact->birthdate->date->month;
    }

    private function getKeyBy(): \Closure
    {
        return fn ($items) => $items->first()->birthdate->date->month - now()->month;
    }
}
