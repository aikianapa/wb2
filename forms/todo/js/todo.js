$(document).on("todo-js",function(){
    var watcher = "#todoLst";
    var $wrapper = $("#todoListWrapper");
    var $list = $wrapper.find(watcher);

    $wrapper.find("#add-todo-form").on("submit",function(){
        $(this).find(".btn").trigger("click");
        return false;
    });

    $(document).on("wb-watcher-done",function(e,params){
        if (params.watcher == watcher && params.item == "_new") {
            $wrapper.find("#add-todo-form #add-todo").val("");
        }
        $wrapper.find("#add-todo-form #add-todo").val("");
        setTimeout(function(){todoFilter();},100);
    });

    $wrapper.find("#todo-status-menu .dropdown-item").on("click",function(){
        $(this).children(".fa").toggleClass("fa-dot-circle-o fa-circle-o");
        todoFilter();
    });


    function todoFilter() {
        $wrapper.find(".card").addClass("d-none");
        $wrapper.find("#todo-status-menu .fa.fa-dot-circle-o").each(function(){
            let status = $(this).data("status");
            $wrapper.find(".card.border-"+status).removeClass("d-none");
        });
    }
    todoFilter();
});
