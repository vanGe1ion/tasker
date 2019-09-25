$(".delete").click(function () {
    DeleteHandler(this);
});


$(".hide").click(function () {
    if($(this).text()==="Все")
        $(this).text("Только актуальные");
    else
        $(this).text("Все");
    ActualTasks(this);
});


$(".add").click(function () {
    $("table").append($("<tr />")
        .append($("<td />").append($("<select />")))
        .append($("<td />"))
        .append($("<td />"))
        .append($("<td />"))
        .append($("<td />"))
        .append($("<td />"))
        .append($("<td class='options' />")
            .append($("<button class='table save'>Сохранить</button>").css("marginRight", "5px").click(function () {
                SaveHandler(this);
            }))
            .append($("<button class='table cancel'>Отмена</button>").click(function () {
                $(this).parent().parent().remove();
            }))
        )
    );
    let select = $("table").children().children(":last").children(":eq(0)").children("select");
    $.each(TaskDict, function (val, task) {
        select.append($("<option value='"+val+"'>"+task["Description"]+"</option>"))
    });

    select.on("change", function () {
        for (let i = 0; i < 4; ++i)
            $(this).parent().siblings(":eq("+i+")").text(TaskDict[$(this).val()][Object.keys(TaskDict[$(this).val()])[i+2]]);
    });
    select.change();
});



function SaveHandler(button) {
    let row = $(button).parent().parent();
    let Task_ID = row.children(":eq(0)").children("select").val();

    let query = "INSERT INTO RST_Employee_Task VALUES ("+Employee_ID+", "+Task_ID+")";


    $.ajax({
        url:"/script/php/ajaxer.php",
        type:"post",
        dataType:"json",
        data:{
            query:query
        },
        beforeSend:function(){
            let mark = 0;
            $.each($("td:first-child"), function (num, td) {
                if ($(td).text() === TaskDict[Task_ID]["Description"]) {
                    alert("Эта задача уже назначена!");
                    mark = 1;
                    return false;
                }
            });
            if(mark === 1)
                return false;
            else
                return true;
        },
        error:function () {
            alert("Ошибка выполнения AJAX-запроса!");
        },
        success:function (res) {
            if(res === false)
                alert("Ошибка выполнения SQL-запроса!");
            else{
                row.attr("id", "row-"+Task_ID);

                row.html("");
                for (let i = 0; i < 5; ++i)
                    row.append($("<td />").text(TaskDict[Task_ID][Object.keys(TaskDict[Task_ID])[i+1]]));

                row.append($("<td />")
                    .append($("<button class='table' onclick=\"window.location.href = '/employee-of-task.php?Task_ID="+Task_ID+"&Description="+TaskDict[Task_ID]["Description"]+"'\">Исполнители</button>"))
                )
                .append($("<td class='options' />")
                    .append($("<button class='table delete'>Снять с задачи</button>").click(function () {
                        DeleteHandler(this);
                    }))
                )
            }
        }
    });
}


function DeleteHandler(button) {
    let row = $(button).parent().parent();
    let Task_ID = +(row.attr("id").split("-")[1]);

    $.ajax({
        url:"/script/php/ajaxer.php",
        type:"post",
        dataType:"json",
        data:{
            query:"DELETE FROM RST_Employee_Task WHERE Task_ID = " + Task_ID + " AND Employee_ID = " + Employee_ID
        },
        error:function () {
            alert("Ошибка выполнения AJAX-запроса!");
        },
        success:function (res) {
            if(res === false)
                alert("Ошибка выполнения SQL-запроса!");
            else
                row.remove();
        }
    });
}


function ActualTasks(button) {
    if($(button).text() === "Все"){
        $.each($("td:nth-of-type(4)"), function (num, td) {
            if($(td).text()!=="В работе" && $(td).children().length == 0)
                $(this).parent().hide();
        })
    }
    else{
        $("td:nth-of-type(4)").parent().show();
    }
}

ActualTasks(".hide");