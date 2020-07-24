{{-- Navigationsleiste --}}
<ul class="nav nav-tabs scrollnav" id="{{ $v_name }}proposalTab" role="tablist">
    @foreach($proposal->getSections($version) as $section)
        <li class="nav-item border border-dark">
            {{-- Beim Vergleich zweier Versionen werden Unterschiede hervorgehoben --}}
            <a class="nav-link @if($loop->first) active @endif @if(isset($diff_sect) && $diff_sect->contains($section->name)) hervorheben @endif"
               aria-selected="false" role="tab" id="{{ $v_name . $section->name }}_tab" data-toggle="tab"
               aria-controls="{{ $v_name . $section->name }}" href="#{{ $v_name . $section->name }}">
                {{ $section->heading }}
            </a>
        </li>
    @endforeach
</ul>
