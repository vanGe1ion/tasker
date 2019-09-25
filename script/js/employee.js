$(".edit").click(function () {
    EditHandler(this);
});

$(".delete").click(function () {
    DeleteHandler(this);
});

$(".add").click(function () {
    let empNum = Number($("tr:last").attr("id").split("-")[1]) + 1;
    $("table").append($("<tr id='row-"+ empNum +"' />")
        .append($("<td />").append($("<input type='text'>")))
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
});

function SaveHandler(button, operation) {
    let row = $(button).parent().parent();
    let Employee_ID = +(row.attr("id").split("-")[1]);
    let Fullname = row.children(":eq(0)").children(":text").val();
    let Position = row.children(":eq(1)").children(":text").val();

    let query = "";
    if(operation === "create")
        query = "INSERT INTO Employee VALUES ("+Employee_ID+", '"+Fullname+"', '"+Position+"')";
    else
        query = "UPDATE Employee SET Fullname='"+Fullname+"', Position='"+Position+"' WHERE Employee_ID=" + Employee_ID;

    $.ajax({
        url:"/script/php/ajaxer.php",
        type:"post",
        dataType:"json",
        data:{
            query:query
        },
        beforeSend:function () {
            if(Fullname === "" || Position === "") {
                alert("Все поля должны быть заполнены!");
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
                    .append($("<td />").text(Fullname))
                    .append($("<td />").text(Position))
                    .append($("<td />")
                        .append($("<button class='table' onclick=\"window.location.href = '/task-of-employer.php?Employee_ID="+Employee_ID+"&Fullname="+Fullname+"'\">Задачи</button>"))
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
    let Employee_ID = +(row.attr("id").split("-")[1]);
    let Fullname = row.children(":eq(0)").text();
    let Position = row.children(":eq(1)").text();

    row.html("")
        .append($("<td />").append($("<input type='text' value='"+Fullname+"'>")))
        .append($("<td />").append($("<input type='text' value='"+Position+"'>")))
        .append($("<td />"))
        .append($("<td class='options' />")
            .append($("<button class='table save'>Сохранить</button>").css("marginRight", "5px").click(function () {
                SaveHandler(this, "update");
            }))
            .append($("<button class='table cancel'>Отмена</button>").click(function () {
                row.html("")
                    .append($("<td />").text(Fullname))
                    .append($("<td />").text(Position))
                    .append($("<td />")
                        .append($("<button class='table' onclick=\"window.location.href = '/task-of-employer.php?Employee_ID="+Employee_ID+"&Fullname="+Fullname+"'\">Задачи</button>"))
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
        )
}

function DeleteHandler(button) {
    let row = $(button).parent().parent();
    let Employee_ID = +(row.attr("id").split("-")[1]);

    $.ajax({
        url:"/script/php/ajaxer.php",
        type:"post",
        dataType:"json",
        data:{
            query:"DELETE FROM Employee WHERE Employee_ID = " + Employee_ID
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
