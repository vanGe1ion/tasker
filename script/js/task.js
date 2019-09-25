$(".edit").click(function () {
    EditHandler(this);
});

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
    let taskNum = Number($("tr:last").attr("id").split("-")[1]) + 1;
    $("table").append($("<tr id='row-"+ taskNum +"' />")
        .append($("<td />").append($("<input type='text'>")))
        .append($("<td />").append($("<input type='date'>")))
        .append($("<td />").append($("<input type='date'>")))
        .append($("<td />").append($("<select class='status' />")))
        .append($("<td />").append($("<input type='text'>")))
        .append($("<td />"))
        .append($("<td class='options' />")
            .append($("<button class='table save'>Сохранить</button>").css("marginRight", "5px").click(function () {
                SaveHandler(this, "create");
            }))
            .append($("<button class='table cancel'>Отмена</button>").click(function () {
                $(this).parent().parent().remove();
            }))
        )
    );
    $.each(StatusDict, function (val, text) {
        $("#row-"+taskNum).children().children(".status").append($("<option value='"+val+"'>"+text+"</option>"))
    });

});

function SaveHandler(button, operation) {
    let row = $(button).parent().parent();
    let Task_ID = +(row.attr("id").split("-")[1]);
    let Description = row.children(":eq(0)").children(":text").val();
    let StartDate = row.children(":eq(1)").children("input").val();
    let EndDate = row.children(":eq(2)").children("input").val();
    let Status = +(row.children(":eq(3)").children("select").val());
    let ResultPointer = row.children(":eq(4)").children(":text").val();

    let query = "";
    if(operation === "create")
        query = "INSERT INTO Task VALUES ("+Task_ID+", '"+Description+"', '"+StartDate+"', '"+EndDate+"', '"+Status+"', '"+ResultPointer+"')";//(EndDate===''?'null':("'"+EndDate+"'"))
    else
        query = "UPDATE Task SET Description='"+Description+"', Start_Date='"+StartDate+"', End_Date='"+EndDate+"', Status_ID='"+Status+"', Result_Pointer='"+ResultPointer+"' WHERE Task_ID=" + Task_ID;

    $.ajax({
        url:"/script/php/ajaxer.php",
        type:"post",
        dataType:"json",
        data:{
            query:query
        },
        beforeSend:function () {
            if(Description === "" || StartDate === "" || EndDate === "") {
                alert("Поля описания, даты назначения и завершения должны быть заполнены!");
                return false;
            }
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
                row.html("")
                    .append($("<td />").text(Description))
                    .append($("<td />").text(ToNormalDate(StartDate)))
                    .append($("<td />").text(ToNormalDate(EndDate)))
                    .append($("<td />").text(StatusDict[Status]))
                    .append($("<td />").text(ResultPointer))
                    .append($("<td />")
                        .append($("<button class='table' onclick=\"window.location.href = '/employee-of-task.php?Task_ID="+Task_ID+"&Description="+Description+"'\">Исполнители</button>"))
                    )
                    .append($("<td class='options' />")
                        .append($("<button class='table edit'>Редактировать</button>").css("marginRight", "5px").click(function () {
                            EditHandler(this);
                        }))
                        .append($("<button class='table delete'>Удалить</button>").click(function () {
                            DeleteHandler(this);
                        }))
                    )
            }
        }
    });
}



function EditHandler(button) {
    let row = $(button).parent().parent();
    let Task_ID = +(row.attr("id").split("-")[1]);
    let Description = row.children(":eq(0)").text();
    let StartDate = row.children(":eq(1)").text();
    let EndDate = row.children(":eq(2)").text();
    let Status = row.children(":eq(3)").text();
    let ResultPointer = row.children(":eq(4)").text();

    row.html("")
        .append($("<td />").append($("<input type='text'>").val(Description)))
        .append($("<td />").append($("<input type='date'>").val(ToUnnormalDate(StartDate))))
        .append($("<td />").append($("<input type='date'>").val(ToUnnormalDate(EndDate))))
        .append($("<td />").append($("<select class='status' />")))
        .append($("<td />").append($("<input type='text'>").val(ResultPointer)))
        .append($("<td />"))
        .append($("<td class='options' />")
            .append($("<button class='table save'>Сохранить</button>").css("marginRight", "5px").click(function () {
                SaveHandler(this, "update");
            }))
            .append($("<button class='table cancel'>Отмена</button>").click(function () {
                row.html("")
                    .append($("<td />").text(Description))
                    .append($("<td />").text(StartDate))
                    .append($("<td />").text(EndDate))
                    .append($("<td />").text(Status))
                    .append($("<td />").text(ResultPointer))
                    .append($("<td />")
                        .append($("<button class='table' onclick=\"window.location.href = '/employee-of-task.php?Task_ID="+Task_ID+"&Description="+Description+"'\">Исполнители</button>"))
                    )
                    .append($("<td class='options' />")
                        .append($("<button class='table edit'>Редактировать</button>").css("marginRight", "5px").click(function () {
                            EditHandler(this);
                        }))
                        .append($("<button class='table delete'>Удалить</button>").click(function () {
                            DeleteHandler(this);
                        }))
                    )
            }))
        );

    $.each(StatusDict, function (val, text) {
        row.children().children(".status").append($("<option value='"+val+"'>"+text+"</option>"))
    });
    row.children().children(".status").children("option:contains('"+Status+"')").attr("selected", "selected");
}

function DeleteHandler(button) {
    let row = $(button).parent().parent();
    let Task_ID = +(row.attr("id").split("-")[1]);

    $.ajax({
        url:"/script/php/ajaxer.php",
        type:"post",
        dataType:"json",
        data:{
            query:"DELETE FROM Task WHERE Task_ID = " + Task_ID
        },
        beforeSend:function () {
            return confirm("Вы действительно хотите удалить запись?");
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

function ToNormalDate(date) {
    let source = new Date(date);
    let day = source.getDate();
    let month = source.getMonth() + 1;
    let year = source.getFullYear();
    return (day<10?"0":"") + day + "." + (month<10?"0":"") + month + "." + year;
}

function ToUnnormalDate(date) {
    let source = date.split(".");
    let day = source[0];
    let month = source[1];
    let year = source[2];
    return year + "-" + month + "-" + day;

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