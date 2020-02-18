$(document).on("smartid-js", function() {
    $(".wb-smartid").on("keypress",function(event){
        let char = String.fromCharCode(event.which);
        if (char == " ") char = "_";
        let result = char.match(/^[а-яА-Яa-zA-Z0-9_-]{1,}$/gm);
        if (result == null) return false;
        var val = $(this).val();
        $(this).val(val + char);
        event.preventDefault();
    });
});
