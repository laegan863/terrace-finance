@props([
    'title' => null,
    'breadcrumbs' => [], // each: ['label' => 'Pages', 'url' => route('...')] or ['label'=>'Current']
])

<div class="page-header">
    <h4 class="page-title">{{ $title ?? trim($__env->yieldContent('page_title', 'Dashboard')) }}</h4>

    @if(!empty($breadcrumbs))
        <ul class="breadcrumbs">
            <li class="nav-home">
                <a href="{{ url('/') }}">
                    <i class="icon-home"></i>
                </a>
            </li>

            @foreach($breadcrumbs as $crumb)
                <li class="separator"><i class="icon-arrow-right"></i></li>
                <li class="nav-item">
                    @if(isset($crumb['url']))
                        <a href="{{ $crumb['url'] }}">{{ $crumb['label'] }}</a>
                    @else
                        <a href="javascript:void(0)">{{ $crumb['label'] }}</a>
                    @endif
                </li>
            @endforeach
        </ul>
    @endif
</div>
