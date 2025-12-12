{{--
  Partial pagination control.
  Usage: @include('partials.pagination', ['paginator' => $items])
  This wrapper centralizes pagination markup so styles and logic stay in one place.
--}}
@if(isset($paginator) && method_exists($paginator, 'hasPages') && $paginator->hasPages())
    <div class="pagination-wrapper">
        <div class="pagination-inner">
            {{-- Preserve query string on links --}}
            {!! $paginator->appends(request()->query())->links() !!}
        </div>
    </div>
@endif
