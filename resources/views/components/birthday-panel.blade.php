@if($showBirthdays)
  <div class="br3 ba b--gray-monica bg-white mb4">
    <div class="pa3 bb b--gray-monica">
      <p class="mb1 b">
        🎂&#8199;{{ __('app.dav_birthdays') }}
      </p>
    </div>
    <div class="pt3 pr3 pl3 mb4">
      @foreach($groupedContacts as $contacts)
        <h3 class="ttu fw5 f5 pb2">{{ \App\Helpers\DateHelper::getMonthAndYearWithFormat($contacts->first()->birthdate->date->month) }}</h3>
        <ul class="mb4">
          @foreach($contacts as $contact)
            <li class="pb2">
              <span class="ttu f6 mr2 black-60">{{ \App\Helpers\DateHelper::getShortDateWithoutYear($contact->birthdate->date) }}</span>
              <span>
                    @if ($contact->is_partial)

                  @php($relatedRealContact = $contact->getRelatedRealContact())
                  <a href="{{ route('people.show', $relatedRealContact) }}">{{ $relatedRealContact->getIncompleteName() }}</a>

                @else

                  <a href="{{ route('people.show', $contact) }}">{{ $contact->getIncompleteName() }}</a>

                @endif
                </span>
              {{ $contactAge($contact) }}
            </li>
          @endforeach
        </ul>
      @endforeach
    </div>
  </div>
@endif
