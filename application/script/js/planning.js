$(".edit").click(function () {
    EditHandler(this);
});

$(".delete").click(function () {
    DeleteHandler(this);
});

$(".add").click(function () {
    let lastRow = $(this).parent().parent().siblings(":last");
    let PlanningNum = lastRow.length? (Number(lastRow.attr("id").split("-")[1]) + 1): 1;
    $(this).parent().parent().before($("<tr id='row-"+ PlanningNum +"' />")
        .append($("<td />").text(PlanningNum))
        .append($("<td />").append($("<select class='form-control form-control-sm task' type='text'>")))
        .append($("<td />").append($("<input class='form-control form-control-sm' type='date'>")))
        .append($("<td class='result'/>").append($("<input class='form-control form-control-sm' type='text'>")))
        .append($("<td class='employeeList'/>")
            .append($("<div />")
                .append($("<div class='btn-group btn-group-sm empAdd' />"))
            )
        )
        .append($("<td class='options' />")
            .append($("<button class='btn btn-secondary btn-sm save'>Сохранить</button>").css("marginRight", "5px").click(function () {
                SaveHandler(this, "create");
            }))
            .append($("<button class='btn btn-secondary btn-sm cancel'>Отмена</button>").click(function () {
                $(this).parent().parent().remove();
            }))
        )
    );

    let select = $("#row-"+PlanningNum).children().children(".task");
    $.each(TaskDict, function (val, text) {
        select.append($("<option value='"+val+"'>"+text+"</option>"))
    });
    select.change(function () {
        TaskSelectHandler(this)
    });
    select.change();
});


function SaveHandler(button, type) {
    let row = $(button).parent().parent();
    let Planning_ID = +(row.attr("id").split("-")[1]);
    let Task = row.children(":eq(1)").children("select").val();
    let Date = row.children(":eq(2)").children("input").val();
    let Result = row.children(":eq(3)").children(":text").val();
    let Employees = row.children(":eq(4)").children().clone();

    $.ajax({
        url:"/application/script/php/ajaxer.php",
        type:"post",
        data:{
            querySet:"PlanningQuerySet",
            queryType:type,
            queryData:{
                Planning_ID:Planning_ID,
                Task:Task,
                Date:Date,
                Result:Result.escapeHTML()
            }
        },
        beforeSend:function () {
            row.children().children("[type='date']").removeClass("is-invalid");
            if(Date === "")
                row.children(":eq(2)").children("input").addClass("is-invalid");
            if(Date === "")
                return false;
            else
                return true;
        },
        error:function () {
            alert("Ошибка выполнения AJAX-запроса!");
        },
        success:function (res) {
            if(res == false)
                alert("Ошибка выполнения SQL-запроса!");
            else{
                Employees.children().children().removeClass("disabled");
                row.html("")
                    .append($("<td />").text(Planning_ID))
                    .append($("<td />").text(TaskDict[Task]))
                    .append($("<td />").text(ToNormalDate(Date)))
                    .append($("<td class='result' />").text(Result))
                    .append($("<td class='employeeList' />").append(Employees))
                    .append($("<td />")
                        .append($("<button class='btn btn-secondary btn-sm edit'>Редактировать</button>").css("marginRight", "5px").click(function () {
                            EditHandler(this);
                        }))
                        .append($("<button class='btn btn-secondary btn-sm delete'>Удалить</button>").click(function () {
                            DeleteHandler(this);
                        }))
                    )
            }
        }
    });
}



function EditHandler(button) {
    let row = $(button).parent().parent();
    let Planning_ID = +(row.attr("id").split("-")[1]);
    let Task = row.children(":eq(1)").text();
    let Date = row.children(":eq(2)").text();
    let Result = row.children(":eq(3)").text();
    let Employees = row.children(":eq(4)").children().clone();

    row.html("")
        .append($("<td />").text(Planning_ID))
        .append($("<td />").append($("<select class='form-control form-control-sm task' type='text'>").val(Task)))
        .append($("<td />").append($("<input class='form-control form-control-sm' type='date'>").val(ToUnnormalDate(Date))))
        .append($("<td class='result' />").append($("<input class='form-control form-control-sm' type='text'>").val(Result)))
        .append($("<td class='employeeList' />").append(Employees))
        .append($("<td />")
            .append($("<button class='btn btn-secondary btn-sm save'>Сохранить</button>").css("marginRight", "5px").click(function () {
                SaveHandler(this, "update");
            }))
            .append($("<button class='btn btn-secondary btn-sm cancel'>Отмена</button>").click(function () {
                Employees.children().children().removeClass("disabled");
                row.html("")
                    .append($("<td />").text(Task))
                    .append($("<td />").text(Date))
                    .append($("<td class='result'/>").text(Result))
                    .append($("<td class='employeeList' />").append(Employees))
                    .append($("<td />")
                        .append($("<button class='btn btn-secondary btn-sm edit'>Редактировать</button>").css("marginRight", "5px").click(function () {
                            EditHandler(this);
                        }))
                        .append($("<button class='btn btn-secondary btn-sm delete'>Удалить</button>").click(function () {
                            DeleteHandler(this);
                        }))
                    )
            }))
        );

    let select = row.children().children(".task");
    $.each(TaskDict, function (val, text) {
        select.append($("<option value='"+val+"'>"+text+"</option>"))
    });
    select.children("option:contains('"+Task+"')").attr("selected", "selected");
    select.change(function () {
        TaskSelectHandler(this)
    });
    select.change();
}

function DeleteHandler(button) {
    let row = $(button).parent().parent();
    let Planning_ID = +(row.attr("id").split("-")[1]);

    $.ajax({
        url:"/application/script/php/ajaxer.php",
        type:"post",
        data:{
            querySet:"PlanningQuerySet",
            queryType:"delete",
            queryData:{
                Planning_ID:Planning_ID
            }
        },
        beforeSend:function () {
            return confirm("Вы действительно хотите удалить запись?");
        },
        error:function () {
            alert("Ошибка выполнения AJAX-запроса!");
        },
        success:function (res) {
            if(res == false)
                alert("Ошибка выполнения SQL-запроса!");
            else
                row.remove();
        }
    });

}


function TaskSelectHandler(select){
    let empBlock = $(select).parent().siblings(":eq(3)").children().html("");
    $.each(EmpBase[$(select).val()], function (id, emp) {
        empBlock.append($("<div class='btn-group btn-group-sm mb-1' />")
            .append($("<button class='btn btn-sm btn-primary empButton'>" + emp + "</button>"))
            .append($("<button id='emp-" + id + "' class='btn btn-sm btn-primary empOperation dismiss' />"))
        )
    })
}
