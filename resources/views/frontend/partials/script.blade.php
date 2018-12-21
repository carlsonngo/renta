<script type="text/javascript" src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>

<script>
var checkout_url = "{{ route('shop.checkout', query_vars()) }}",
    shop_url = "{{ route('shop.index', query_vars()) }}",
    cart_url = "{{ route('shop.cart', query_vars()) }}",
    cart_totals_url = "{{ route('shop.cart.totals') }}",
    currency_symbol = "{{ currency_symbol(@App\Setting::get_setting('currency')) }}";
</script>

<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script type="text/javascript" src="{{ asset('assets/js/popper.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/jquery-ui.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/mask/jquery.inputmask.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('plugins/select2/select2.min.js') }}"></script>    
<script type="text/javascript" src="{{ asset('plugins/inputmask/jquery.inputmask.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/frontend.js') }}?v={{ filemtime(public_path('js/frontend.js')) }}"></script>
<script type="text/javascript" src="{{ asset('js/shop.js') }}?v={{ filemtime(public_path('js/shop.js')) }}"></script>

@yield('plugin_script')

@yield('script')

@if( App\Setting::get_setting('google_translate') )
<script type="text/javascript">
function googleTranslateElementInit() {
    new google.translate.TranslateElement({
        pageLanguage: '{{ App\Setting::get_setting('site_language') }}', 
        includedLanguages: 'de,el,en,es,fr,it,nl,no,pl,pt,sv'
    }, 'google_translate_element');
}
</script>
<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
@endif