$(".edit").click(function () {
    EditHandler(this);
});

$(".delete").click(function () {
    DeleteHandler(this);
});

$(".add").click(function () {
    let lastRow = $(this).parent().parent().siblings(":last");
    let empNum = lastRow.length? (Number(lastRow.attr("id").split("-")[1]) + 1): 1;
    $(this).parent().parent().before($("<tr id='row-"+ empNum +"' />")
        .append($("<td />").text(empNum))
        .append($("<td />").append($("<input class='form-control form-control-sm' type='text'>")))
        .append($("<td />").append($("<input class='form-control form-control-sm' type='text'>")))
        .append($("<td />")
            .append($("<button class='btn btn-secondary btn-sm save'>Сохранить</button>").css("marginRight", "5px").click(function () {
                SaveHandler(this, "create");
            }))
            .append($("<button class='btn btn-secondary btn-sm cancel'>Отмена</button>").click(function () {
                $(this).parent().parent().remove();
            }))
        )
    );
});

function SaveHandler(button, operation) {
    let row = $(button).parent().parent();
    let Employee_ID = +(row.attr("id").split("-")[1]);
    let Fullname = row.children(":eq(1)").children(":text").val();
    let Position = row.children(":eq(2)").children(":text").val();

    let query = "";
    if(operation === "create")
        query = "EmployeeCreate";
    else
        query = "EmployeeEdit";

    $.ajax({
        url:"/script/php/ajaxer.php",
        type:"post",
        data:{
            queryName:query,
            data:{
                Employee_ID:Employee_ID,
                Fullname:Fullname,
                Position:Position
            }
        },
        beforeSend:function () {
            row.children().children(":text").removeClass("is-invalid");
            if(Fullname === "")
                row.children(":eq(1)").children(":text").addClass("is-invalid");
            if(Position === "")
                row.children(":eq(2)").children(":text").addClass("is-invalid");
            if(row.children().children(":text").hasClass("is-invalid"))
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
                row.html("")
                    .append($("<td />").text(Employee_ID))
                    .append($("<td />").text(Fullname))
                    .append($("<td />").text(Position))
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
    let Employee_ID = +(row.attr("id").split("-")[1]);
    let Fullname = row.children(":eq(1)").text();
    let Position = row.children(":eq(2)").text();

    row.html("")
        .append($("<td />").text(Employee_ID))
        .append($("<td />").append($("<input class='form-control form-control-sm' type='text' value='"+Fullname+"'>")))
        .append($("<td />").append($("<input class='form-control form-control-sm' type='text' value='"+Position+"'>")))
        .append($("<td />")
            .append($("<button class='btn btn-secondary btn-sm save'>Сохранить</button>").css("marginRight", "5px").click(function () {
                SaveHandler(this, "update");
            }))
            .append($("<button class='btn btn-secondary btn-sm cancel'>Отмена</button>").click(function () {
                row.html("")
                    .append($("<td />").text(Fullname))
                    .append($("<td />").text(Position))
                    .append($("<td />")
                        .append($("<button class='btn btn-secondary btn-sm edit'>Редактировать</button>").css("marginRight", "5px").click(function () {
                            EditHandler(this);
                        }))
                        .append($("<button class='btn btn-secondary btn-sm delete'>Удалить</button>").click(function () {
                            DeleteHandler(this);
                        }))
                    )
            }))
        )
}

function DeleteHandler(button) {
    let row = $(button).parent().parent();
    let Employee_ID = +(row.attr("id").split("-")[1]);
    $.ajax({
        url:"/script/php/ajaxer.php",
        type:"post",
        data:{
            queryName:"EmployeeRemove",
            data:{
                Employee_ID:Employee_ID
            }
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
