$(function(){

    $("#find").click(getMovies);

    function getMovies() {
        var year = $("#year").val();

        $(".movies").empty();
        $(".error").empty();
        $(".error").hide();

        $.ajax({
            type: 'POST',
            url: '/search.php',
            dataType: 'json',
            data: {
                year: year
            },
            success: function(result){
                if('error' in result){
                    showError(result["error"]);
                } else if(result.length > 0){
                    showMovies(result, year);
                }
            }
        })
    }

    function showMovies(items, year){
        $(".movies").append("<h2>Фильмы "+year+" года:");
        $(".movies").append("<table><tr><th>Название</th><th>Ссылка</th><th>Рейтинг</th></tr></table>");

        for(var i = 0; i<items.length; i++){
            var newRow = "<tr><td>"+
                items[i]['name']+
                "</td><td><a href='"+items[i]['link']+
                "'>Ссылка</a></td><td>"+items[i]['rating']+
                "</td></tr>";

            $(".movies table").append(newRow);
        }
    }

    function showError(error){
        $(".error").append("<p>"+error+"</p>");
        $(".error").show();
    }

    $(document).ajaxStart(function() {
        $( ".wait" ).show();
    });

    $(document).ajaxComplete(function(){
        $(".wait").hide();
    });
});