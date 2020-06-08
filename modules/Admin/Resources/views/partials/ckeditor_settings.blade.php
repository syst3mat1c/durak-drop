<script src="{{ asset('adminlte/dependencies/ckeditor/ckeditor.js') }}"></script>
<script>
    $(function() {
        $('textarea').each(function() {
            CKEDITOR.replace($(this).prop('id'));
        });
    });
</script>
