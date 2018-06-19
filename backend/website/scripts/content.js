function loading() {
    window.top.loading();
}
function hideLoading() {
    window.top.hideLoading();
}
function info(text) {
    window.top.info(text);
}
function error(text) {
    window.top.error(text);
}
function success(text) {
    window.top.success(text);
}
function confirm(text, confirm) {
    window.top.confirm(text, confirm);
}
function openWindow(options) {
    window.top.openWindow(options);
}
function openTab(href, title) {
    window.top.openTab(href, title);
}
function closeCurrentWindow() {
    window.top.closeCurrentWindow();
}
function closeAllWindow() {
    window.top.closeAllWindow();
}
function refreshCurrentTab() {
    if (window.top.refreshCurrentTab) {
        window.top.refreshCurrentTab();
    }
}
function reloadCurrentTab(href, title) {
    window.top.reloadCurrentTab(href, title);
}
function ajaxRefresh() {
    var $form = $("#content").find("form:first");
    var options = {
        type: $form ? "POST" : "GET",
        url: $form ? $form.attr("action") : window.location.href,
        data: $form ? $form.serialize() : {},
        dataType: "html", beforeSend: function () {
            loading();
        }, error: function (XMLHttpRequest, textStatus, errorThrown) {
            hideLoading();
            error(XMLHttpRequest.responseText?XMLHttpRequest.responseText:"刷新失败，请稍候再试");
        }, success: function (html) {
            hideLoading();
             $("#content").html(html).find(".fixed-header-table").each(function(){
                $(this).parents(".list-body").height($(window).height()-$(".list-header").height()-$(".list-footer").height()-50);
                $(this).fixedHeaderTable();
            })
        }
    };
    $.ajax(options);
    return false;
}

function todo(obj) {
    var options={
        type: "POST",
        url: $(obj).data("url") || $(obj).attr("href"),
        data: $(obj).data("data") || {id: $(obj).data("id")},
        dataType: "json", beforeSend: function () {
            loading();
        }, error: function (XMLHttpRequest, textStatus, errorThrown) {
            hideLoading();
            error(XMLHttpRequest.responseText?XMLHttpRequest.responseText:"提交失败，请稍候再试");
        }, success: function (data) {
            hideLoading();
            if (data && data.status == 200) {
                success(data && data.msg ? data.msg : (($(obj).attr("title") || $(obj).text()) + "成功"));
                if (data.callback == "close_all_and_refresh") {
                    refreshCurrentTab();
                    closeAllWindow();
                } else if (data.callback == "close_and_refresh") {
                    refreshCurrentTab();
                    closeCurrentWindow();
                } else if (data.callback == "refresh") {
                    refreshCurrentTab();
                } else if (data.callback) {
                    eval("var callback=" + data.callback + ";if(window.callback){window.callback(data);}");
                }
            } else {
                error(data && data.msg ? data.msg : "提交失败，请稍候再试");
            }
        }
    };
    $.ajax(options);
}

function ajaxTodo() {
    var obj = this;
    if ($(this).hasClass('confirm')) {
        var title = $(this).data("title");
        if (!title) {
            title = $(this).attr("title") || $(this).text();
            title = "您确定要" + title + "?";
        }
        confirm(title, function () { todo(obj); })
    } else {
        todo(obj);
    }
    return false;
}

function ajaxSubmit() {
    var $form = $(this);
    var options = {
        type: "POST",
        url: $form.attr("action"),
        data: $form.serialize(),
        dataType: "json", beforeSend: function () {
            loading();
        }, error: function (XMLHttpRequest, textStatus, errorThrown) {
            hideLoading();
            error(XMLHttpRequest.responseText?XMLHttpRequest.responseText:"提交失败，请稍候再试");
        }, success: function (data) {
            hideLoading();
            if (data && data.status == 200) {
                success(data && data.msg ? data.msg : "提交成功");
                if (data.callback == "close_all_and_refresh") {
                    refreshCurrentTab();
                    closeAllWindow();
                } else if (data.callback == "close_and_refresh") {
                    refreshCurrentTab();
                    closeCurrentWindow();
                } else if (data.callback == "refresh") {
                    refreshCurrentTab();
                } else if (data.callback) {
                    eval("var callback=" + data.callback + ";if(window.callback){window.callback(data);}");
                }
            } else {
                error(data && data.msg ? data.msg : "提交失败，请稍候再试");
            }
        }
    };
    $.ajax(options);
    return false;
}
$(function () {
    $("#content").on("click", "a.J_menuItem", function () {
        var href = $(this).attr("href");
        var title = $(this).attr("title") || $(this).text();
        openTab(href, title);
        return false;
    }).on("click", ".dialog", function () {
        openWindow({
            full: $(this).data("full") == true,
            type: 2,
            title: $(this).attr("title"),
            shade: 0.3,
            area: [($(this).data("width") || 600) + "px", ($(this).data("height") || 500) + "px"],
            content: $(this).data("url") ||this.href
        });
        return false;
    })  .on("click", ".ajax-refresh", ajaxRefresh)
        .on("click", ".ajax-todo", ajaxTodo)
        .on("beforeSubmit", ".ajax-form form", ajaxSubmit)
        .on("click", ".pagination a", function () {
            $("form:first").attr("action", $(this).attr('href'));
            ajaxRefresh();
            return false;
        }).on("change", ".pagination-custom select", function () {
        ajaxRefresh();
        return false;
    }).find(".fixed-header-table").each(function(){
        $(this).parents(".list-body").height($(window).height()-$(".list-header").height()-$(".list-footer").height()-50);
        $(this).fixedHeaderTable();
    });
    $(".image").mouseenter(function () {
        $(this).find(".select").css("z-index", 100);
    }).mouseleave(function () {
        $(this).find(".select").css("z-index", 0);
    }).on("click", "a.edit", function () {
        var obj = this;
        var img = $(this).parent().prev().find("img");
        var $container = $(this).parents('.image');

        if (img.length) {
            var width = $container.data("width");
            var height = $container.data("height");
            var canvasWidth = $container.data("img-width") || width;
            var canvasHeight = $container.data("img-height") || height;

            var $img = $("<img id='cut-img' />").attr("src", img.attr("src"));
            var $html = $($("#img-edit-template").html());

            $img.bind("load", function () {
                $("#cut-img").cropper({aspectRatio: width / height});
            });
            $html.find(".img-container").width(width + 2).height(height + 2).html($img);
            layer.open({
                type: 1,
                title: false,
                closeBtn: 0,
                shadeClose: true,
                area: [width + 2 + 'px', height + 50 + 'px'],
                content: $html.html()
            });
            $("#confirm-cut-img").bind("click", function () {
                var data = $("#cut-img").cropper("getData");
                var canvas = document.createElement("canvas");
                canvas.width = canvasWidth;
                canvas.height = canvasHeight;
                canvas.getContext("2d").drawImage($("#cut-img").get(0), data.x, data.y, data.width, data.height, 0, 0, canvas.width, canvas.height);
                img.attr("src", canvas. toDataURL("image/png"));

                var $hidden = $(obj).parent().prev().find("input");
                if (!$hidden.length) {
                    var name = $container.data("name") || 'image';
                    $hidden = $("<input type='hidden' />").attr("name", name).appendTo($(obj).parent().prev());
                }
                $hidden.val(img.attr("src"));
                layer.closeAll();
            })
        }
    }).on("click", "a.delete", function () {
        var $container = $(this).parents('.image');
        var $hidden = $(this).parent().prev().find("input");
        if (!$hidden.length) {
            var name = $container.data("name") || 'image';
            $hidden = $("<input type='hidden' />").attr("name", name).appendTo($(obj).parent().next());
        }
        $hidden.val(null);

        var $img = $(this).parent().prev().find("img");
        if ($img.length) {
            $img.remove();
        }

        $(this).parent().hide();
    });

    $(".image input:file").bind("change", function () {
        var obj = this;
        var $container = $(this).parents('.image');
        var expectWidth = $container.data("img-width") || $container.data("width");
        var expectHeight = $container.data("img-height") || $container.data("height");
        if (this.files.length) {
            var reader = new FileReader();
            reader.onload = function (evt) {
                var $img = $("<img />").attr("src", evt.target.result);
                $img.bind("load", function () {
                    var top = 0, left = 0, width, height, canvasWidth, canvasHeight;

                    if (this.naturalWidth / this.naturalHeight > expectWidth / expectHeight) {
                        width = canvasWidth = Math.max(this.naturalWidth, expectWidth);

                        canvasHeight = canvasWidth * (expectHeight / expectWidth);
                        height = width * (this.naturalHeight / this.naturalWidth);

                        top = ( canvasHeight - height) / 2
                    } else {
                        height = canvasHeight = Math.max(this.naturalHeight, expectHeight);

                        canvasWidth = height * (expectWidth / expectHeight);
                        width = height * (this.naturalWidth / this.naturalHeight);

                        left = (canvasWidth - width) / 2;
                    }

                    var canvas = document.createElement("canvas");
                    canvas.width = canvasWidth;
                    canvas.height = canvasHeight;
                    canvas.getContext("2d").drawImage($img.get(0), 0, 0, this.naturalWidth, this.naturalHeight, left, top, width, height);

                    var data = canvas. toDataURL("image/png");
                    var $hidden = $container.find("input:hidden");
                    if (!$hidden.length) {
                        var name = $container.data("name") || 'image';
                        $hidden = $("<input type='hidden' />").attr("name", name).appendTo($(obj).parent().next());
                    }
                    $hidden.val(data);

                    var $new_img = $container.find("img");
                    if (!$new_img.length) {
                        $new_img = $("<img />").appendTo($(obj).parent().next());
                    }
                    $new_img.attr("src", data);
                    $(obj).parent().next().next().show();
                });
                $("#temp").html($img);
            };
            reader.readAsDataURL(obj.files[0]);
        }
    })
});