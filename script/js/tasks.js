$(".edit").click(function () {
    EditHandler(this);
});

$(".delete").click(function () {
    DeleteHandler(this);
});

$("a.dropdown-item").click(function (e) {
    e.preventDefault();
    DropdownItemHandler(this);
});

$(".dismiss").click(function () {
    EmployerDismissHandler(this);
});

$(".add").click(function () {
    let lastRow = $(this).parent().parent().siblings(":last");
    let taskNum = lastRow.length? (Number(lastRow.attr("id").split("-")[1]) + 1): 1;
    $(this).parent().parent().before($("<tr id='row-"+ taskNum +"' />")
        .append($("<td />").text(taskNum))
        .append($("<td />").append($("<input class='form-control form-control-sm' type='text'>")))
        .append($("<td />").append($("<input class='form-control form-control-sm' type='date'>")))
        .append($("<td />").append($("<input class='form-control form-control-sm' type='date'>")))
        .append($("<td />").append($("<select class='form-control form-control-sm status' />")))
        .append($("<td class='result'/>").append($("<input class='form-control form-control-sm' type='text'>")))
        .append($("<td class='employeeList' />")
            .append($("<div />")
                .append($("<div class='btn-group btn-group-sm empAdd' />")
                    .append($("<button class='btn btn-sm btn-success empButton disabled'>Назначить</button>"))
                    .append($("<button class='btn btn-sm btn-success dropdown-toggle dropdown-toggle-split empOperation disabled'  data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'></button>"))
                    .append($("<div class='dropdown-menu dropdown-menu-right' />"))
                )
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

    let select = $("#row-"+taskNum).children().children(".status");
    $.each(StatusDict, function (val, text) {
        select.append($("<option value='"+val+"'>"+text+"</option>"))
    });
    let newEmp = $("#row-"+taskNum+" .empAdd .dropdown-menu");
    $.each(EmpDict, function (key, emp) {
        newEmp.append($("<a id='newemp-"+key+"' href='#' class='dropdown-item'>"+emp+"</a>"))
    });
});

function SaveHandler(button, type) {
    let row = $(button).parent().parent();
    let Task_ID = +(row.attr("id").split("-")[1]);
    let Description = row.children(":eq(1)").children(":text").val();
    let StartDate = row.children(":eq(2)").children("input").val();
    let EndDate = row.children(":eq(3)").children("input").val();
    let Status = +(row.children(":eq(4)").children("select").val());
    let ResultPointer = row.children(":eq(5)").children(":text").val();
    let Employees = row.children(":eq(6)").children().clone();
    Employees.children(".empAdd").children("div").children().click(function (e) {
        e.preventDefault();
        DropdownItemHandler(this);
    });

    $.ajax({
        url:"/script/php/ajaxer.php",
        type:"post",
        data:{
            querySet:"TaskQuerySet",
            queryType:type,
            queryData:{
                Task_ID:Task_ID,
                Description:Description.escapeHTML(),
                Start_Date:StartDate,
                End_Date:EndDate,
                Status:Status,
                Result_Pointer:ResultPointer.escapeHTML()
            }
        },
        beforeSend:function () {
            row.children().children(":text, [type='date']").removeClass("is-invalid");
            if(Description === "")
                row.children(":eq(1)").children(":text").addClass("is-invalid");
            if(StartDate === "")
                row.children(":eq(2)").children("input").addClass("is-invalid");
            if(Description === "" || StartDate === "")
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
                row.removeAttr("class").addClass(StatusBase[StatusDict[Status]]).html("")
                    .append($("<td />").text(Task_ID))
                    .append($("<td />").text(Description))
                    .append($("<td />").text(ToNormalDate(StartDate)))
                    .append($("<td />").text(ToNormalDate(EndDate)))
                    .append($("<td />").text(StatusDict[Status]))
                    .append($("<td class='result' />").text(ResultPointer))
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
    let Task_ID = +(row.attr("id").split("-")[1]);
    let Description = row.children(":eq(1)").text();
    let StartDate = row.children(":eq(2)").text();
    let EndDate = row.children(":eq(3)").text();
    let Status = row.children(":eq(4)").text();
    let ResultPointer = row.children(":eq(5)").text();
    let Employees = row.children(":eq(6)").children().clone();
    Employees.children().children().addClass("disabled");

    row.html("")
        .append($("<td />").text(Task_ID))
        .append($("<td />").append($("<input class='form-control form-control-sm' type='text'>").val(Description)))
        .append($("<td />").append($("<input class='form-control form-control-sm' type='date'>").val(ToUnnormalDate(StartDate))))
        .append($("<td />").append($("<input class='form-control form-control-sm' type='date'>").val(ToUnnormalDate(EndDate))))
        .append($("<td />").append($("<select class='status form-control form-control-sm' />")))
        .append($("<td class='result' />").append($("<input class='form-control form-control-sm' type='text'>").val(ResultPointer)))
        .append($("<td class='employeeList' />").append(Employees))
        .append($("<td />")
            .append($("<button class='btn btn-secondary btn-sm save'>Сохранить</button>").css("marginRight", "5px").click(function () {
                SaveHandler(this, "update");
            }))
            .append($("<button class='btn btn-secondary btn-sm cancel'>Отмена</button>").click(function () {
                Employees.children().children().removeClass("disabled");
                row.html("")
                    .append($("<td />").text(Description))
                    .append($("<td />").text(StartDate))
                    .append($("<td />").text(EndDate))
                    .append($("<td />").text(Status))
                    .append($("<td class='result'/>").text(ResultPointer))
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
        data:{
            querySet:"TaskQuerySet",
            queryType:"delete",
            queryData:{
                Task_ID:Task_ID
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

function DropdownItemHandler(item) {
    let row = $(item).parentsUntil("tbody").last();
    let taskID = row.attr("id").split("-")[1];
    let empID = $(item).attr("id").split("-")[1];

    $.ajax({
        url:"/script/php/ajaxer.php",
        type:"post",
        dataType:"json",
        data:{
            querySet:"RSTEmployeeTaskQuerySet",
            queryType:"create",
            queryData:{
                Employee_ID:empID,
                Task_ID:taskID
            }
        },
        error:function () {
            alert("Ошибка выполнения AJAX-запроса!");
        },
        success:function (res) {
            if(res == false)
                alert("Ошибка выполнения SQL-запроса!");
            else{
                $(item).addClass("d-none");
                let btngp = $(item).parent().parent();
                btngp.before($("<div class='btn-group btn-group-sm mb-1' />")
                    .append($("<button class='btn btn-sm btn-primary empButton'>"+$(item).text()+"</button>"))
                    .append($("<button id='emp-"+empID+"' class='btn btn-sm btn-primary empOperation dismiss'>x</button>").click(function () {
                        EmployerDismissHandler(this);
                    }))
                )
            }
        }
    });
}

function EmployerDismissHandler(button) {
    let row = $(button).parentsUntil("tbody").last();
    let taskID = row.attr("id").split("-")[1];
    let empID = $(button).attr("id").split("-")[1];
    let fullname =  $(button).siblings().text();

    $.ajax({
        url:"/script/php/ajaxer.php",
        type:"post",
        dataType:"json",
        data:{
            querySet:"RSTEmployeeTaskQuerySet",
            queryType:"delete",
            queryData:{
                Employee_ID: empID,
                Task_ID:taskID
            }
        },
        error:function () {
            alert("Ошибка выполнения AJAX-запроса!");
        },
        success:function (res) {
            if(res == false)
                alert("Ошибка выполнения SQL-запроса!");
            else{
                $(button).parent().siblings(":last").children("div").children(":contains("+fullname+")").removeClass("d-none");
                $(button).parent().remove();
            }
        }
    });
}