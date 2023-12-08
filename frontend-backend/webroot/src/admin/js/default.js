import "../../js/jquery/leak_jquery";
import "../../js/jquery/jquery-migrate-1.4.1.min";
import "../../js/leak_mustache";

import $ from "jquery";

$(function () {
  $("html").removeClass("no-js").addClass("js");
  if ($.browser.msie) $("html").addClass("ie");
  if ($.browser.opera) $("html").addClass("opera");
  if ($.browser.mozilla) $("html").addClass("firefox");
  if ($.browser.webkit) $("html").addClass("webkit");
  if (navigator.appVersion.indexOf("Win") != -1) $("html").addClass("win");
  if (navigator.appVersion.indexOf("Mac") != -1) $("html").addClass("osx");
  if (navigator.appVersion.indexOf("Linux") != -1) $("html").addClass("linux");

  for (let i = 0, a = document.links; i < a.length; i++) if (a[i].rel && a[i].rel == "external") a[i].target = "_blank";

  if ($("div.message").length > 0) {
    const $msg = $("div.message").addClass("alert alert-warning");
    $msg.html(
      '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' + $msg.html()
    );
    setTimeout(() => $("div.message").remove(), 10 * 1000);
  }

  $(document.body)
    .ajaxStart(() => showLoading())
    .ajaxComplete(() => hideLoading());

  arrangeTable();
  arrangeForm();
  arrangePagination();
});

function arrangeForm(formBase) {
  function fixCheckbox($input) {
    const $label = $input.find("label");
    const $theInput = $input.find("input[type='checkbox']");
    $label.replaceWith($label.clone().html($theInput[0].outerHTML + " " + $label.text()));
    $theInput.remove();
  }

  if (!formBase) {
    formBase = $("#content form");
  }

  formBase.each(function () {
    const form = this;
    $(".input-view", form).each(function () {
      const $input = $(this).addClass("form-group");
      const $error = $input.hasClass("error");

      $.each(($input.find("> p").not("[type=hidden]").attr("class") || "").split(" "), (i, $klass) => {
        if ($klass.indexOf("col-") > -1) {
          $input.addClass($klass).find("> p").removeClass($klass);
        }
      });

      $input.find("> p").addClass("form-control-static");
      $input.find("label").addClass("control-label");

      if ($error) {
        $input.addClass("error has-error").find(".error-message").addClass("help-block");
      }
    });
    $(".input", form).each(function () {
      const $input = $(this).addClass("form-group");
      const $inline = $input.find(":input").hasClass("input-inline");
      const $error = $input.hasClass("error");

      $.each($input.find(":input").not("[type=hidden]"), function (i) {
        const $klass = $(this).attr("class") || "";
        if ($klass.indexOf("col-") > -1) {
          let bc = $klass.match(/(col\-.*\-[0-9]+)/gi);
          $(this).removeClass(bc[0]).parents(".input").addClass(bc[0]);
        } else {
          $(this).parents(".input").addClass("col-md-12");
        }
      });

      const ib = $input.filter(".checkbox");
      if (!!ib.length) {
        if (ib.hasClass("checkbox") && ib.removeClass("checkbox")) {
          fixCheckbox($input);
        }
        return;
      }

      if ($input.find("> .checkbox").length) {
        $input.find("> .checkbox").each(function () {
          const $check = $(this);
          fixCheckbox($check);
        });
        return;
      }

      $input.find(":input").addClass("form-control");
      $input.find("label").addClass("control-label");

      if ($error) {
        $input.addClass("error has-error").find(".error-message").addClass("help-block");
      }

      if ($inline) {
        $input.addClass("form-group-inline");
      }
    });
    $(".input-select", form).each(function () {
      $(this).selectComponent();
    });
    $(".input .image").on("click", "a.btn-danger", function (event) {
      event.preventDefault();

      var a = $(this);
      if (confirm("Você confirma a exclusão desta imagem?")) {
        a.addClass("disabled");

        $.getJSON(a.attr("href"), function (data) {
          a.removeClass("disabled").parents(".input").remove();
        });
      }
    });
    if ($(form).filter("[data-validate]").length) {
      $(form).validate();
    }

    $(form).addClass("arranged");
  });

  formBase.find(".btn-now").on("click", function (event) {
    event.preventDefault();

    function z(n) {
      return Number(n) < 10 ? "0" + n : n;
    }

    const now = new Date();

    const $p = $(this).parent();
    const dayField = $p.find("[id*=Day]");
    const monthField = $p.find("[id*=Month]");
    const yearField = $p.find("[id*=Year]");
    const hourField = $p.find("[id*=Hour]");
    const minField = $p.find("[id*=Min]");

    dayField.val(z(now.getDate()));
    monthField.val(z(now.getMonth() + 1));
    yearField.val(now.getFullYear());
    hourField.val(z(now.getHours()));
    minField.val(z(now.getMinutes()));
  });

  formBase.filter("[data-search]").on("submit", function (event) {
    event.preventDefault();

    const $form = $(event.target);
    const $url = $form.attr("action");
    const $model = $form.data("search");
    const params = [];

    $(":input", $form).each(function () {
      const $input = $(this);
      const $val = $input.val();
      const $name = $input.attr("name");
      const $type = $input.attr("type");
      if ($type == "checkbox" || $type == "radio") {
        if ($input.filter(":checked").length) params.push([$input.attr("name"), $val].join(":"));
      } else if ($val != null && $name != null) {
        params.push([$input.attr("name"), $val].join(":"));
      }
    });

    window.location.href = $url + "/" + params.join("/");
  });

  $("form select").find("option:odd").addClass("odd");
  $("form select[id$=Hour]").css({ "margin-left": "10px", "margin-right": "4px" });
  $("form select[id$=Min]").css({ "margin-left": "4px" });

  function _toogleInputWatchers() {
    $("form [data-watch]").each(function (_, w) {
      let $w = $(w);
      if (
        $w.data("watch") &&
        (($w.data("if") && $($w.data("watch")).val() === $w.data("if")) ||
          ($w.data("not") && $($w.data("watch")).val() !== $w.data("not")))
      ) {
        $w.removeClass("hide").find(":input").removeAttr("disabled");
      } else {
        $w.addClass("hide").find(":input").attr("disabled", true);
      }
    });
  }
  $(document.body).on("DOMchange", function (event) {
    _toogleInputWatchers();
  });

  $("form [data-watch]").each(function (_, w) {
    $("form").on("change", $(w).data("watch"), function (event) {
      $(document.body).trigger("DOMchange");
    });
  });

  $(document.body).trigger("DOMchange");

  $("#content").on("click", ".image .thumbnail a", function (event) {
    event.preventDefault();

    if (confirm("Você confirma a exclusão desta imagem?")) {
      const img = $(event.target).parents(".image");
      $.getJSON($(event.target).attr("href"), function () {
        img.remove();
      });
    }
  });

  $("#content textarea.editor").ckeditor(function () {
    CKFinder.SetupCKEditor(this, {
      BasePath: $.url(true) + "ckeditor/ckfinder/",
    });
  });

  $("#content textarea.editor-light").ckeditor(
    function () {
      CKFinder.SetupCKEditor(this, {
        BasePath: $.url(true) + "ckeditor/ckfinder/",
      });
    },
    {
      toolbar: [
        { name: "clipboard", items: ["Undo", "Redo"] },
        { name: "basicstyles", items: ["Bold", "Italic", "Strike", "-", "RemoveFormat"] },
        { name: "paragraph", items: ["NumberedList", "BulletedList"] },
        { name: "links", items: ["Link", "Unlink"] },
        { name: "insert", items: ["Image", "Embed", "Quote"] },
      ],
      height: 226,
    }
  );
}

function arrangePagination() {
  $(".pagination").each(function () {
    const pagination = $(this);

    const current = pagination.find(".current");
    if (current.length) current.addClass("active");

    let lis;
    (lis = pagination.find("li")).each(function () {
      const li = $(this);
      if (!li.find("a").length) li.html("<a href='#'>" + li.text() + "</a>");
    });

    if (!lis.length || lis.length < 3) {
      pagination.remove();
      return false;
    }

    pagination.on("click", 'a[href="#"]', function (event) {
      event.preventDefault();
    });
  });
}

function arrangeTable() {
  $("table.table thead tr th:not(.actions)")
    .has("a")
    .addClass("header")
    .click(function (event) {
      event.preventDefault();
      window.location.href = $(this).find("a").attr("href");
    });

  $("table.table thead tr th:not(.actions) > a").each(function () {
    if ($(this).hasClass("desc")) {
      $(this).prepend("<span class='pull-right glyphicon glyphicon-arrow-down'></span>");
    } else if ($(this).hasClass("asc")) {
      $(this).prepend("<span class='pull-right glyphicon glyphicon-arrow-up'></span>");
    }
  });

  $("table.table tbody td.actions").each(function () {
    const btnGroup = $("<div class='btn-group'></div>");

    const ac = $("> a", this).each(function () {
      const a = $(this);
      const klass = ["btn", "btn-xs"];

      a.attr("title", a.hasClass("disabled") ? "[desabilitado]" : a.attr("title") != null ? a.attr("title") : a.text())
        .attr("rel", "tooltip")
        .tooltip();

      if (a.attr("href").indexOf("delete") > -1) {
        a.html("<span class='glyphicon glyphicon-trash'></span> " + a.text()).addClass("btn-danger");
        if (!a.hasClass("disabled")) {
          a.click(function () {
            return confirm("Você confirma a exclusão deste registro?");
          });
        }
      } else if (a.attr("href").indexOf("edit") > -1) {
        a.html("<span class='glyphicon glyphicon-pencil'></span> " + a.text()).addClass("btn-info");
      } else if (a.attr("href").indexOf("view") > -1) {
        a.html("<span class='glyphicon glyphicon-zoom-in'></span> " + a.text()).addClass("btn-primary");
      } else {
        a.addClass("btn-default");
      }

      btnGroup.append(a.addClass(klass.join(" ")));
    }).length;

    $(this).html(ac > 1 ? btnGroup : btnGroup.html());
  });

  $("#content table tr:odd").addClass("odd");
}

function CKupdate() {
  for (var instance in CKEDITOR.instances) CKEDITOR.instances[instance].updateElement();
}
window.CKupdate = CKupdate;

$.url = function (webroot) {
  return $("body").data(!webroot ? "uri" : "webroot");
};

function fz(n) {
  return Number(n) < 10 ? "0" + n : "" + n;
}
window.fz = fz;

function ls(s, onComplete, cache) {
  return $.ajax({
    dataType: "script",
    cache: cache || true,
    url: $.url(true) + "js/" + (s.indexOf(".") > -1 ? s : s + ".js"),
  }).done(function (script, textStatus) {
    (onComplete || new Function())(script);
  });
}
window.ls = ls;

function showLoading() {
  $("#activity").removeClass("disabled").show();
}
window.showLoading = showLoading;

function hideLoading() {
  $("#activity").addClass("disabled").hide();
}
window.hideLoading = hideLoading;

function chr(n) {
  return String.fromCharCode(n) || null;
}
window.chr = chr;

function empty(o) {
  return typeof o == "undefined" || o == null || o.length < 1 || false;
}
window.empty = empty;

String.prototype.truncate = function (l, e) {
  var $s = jQuery.trim(this),
    SPACE = String.fromCharCode(32);
  if (($tmp = $s.substr(0, (l = l || 10)).lastIndexOf(SPACE)) > 0 && $s.length >= l) {
    $s = jQuery.trim($s.substr(0, $tmp));
  }

  return $s == this ? $s : $s + (e || "...");
};

/* CakePHP Integration */
String.prototype.camelize = function (noLowerFirst) {
  for (var i = 0, s = this.replace(/[_.]+/g, "/").split("/"), ns = ""; i < s.length; i++) {
    if (!noLowerFirst && i < 1) {
      ns += s[i].toLowerCase();
    } else {
      ns += (ss = s[i]).substr(0, 1).toUpperCase() + ss.substr(1, ss.length).toLowerCase();
    }
  }
  return ns;
};
function $cake(s) {
  return $("#" + s.camelize(true));
}

function $cakeName(s) {
  s = s.replace(/[_.]+/g, "/").split("/");
  for (var i = 0; i < s.length; i++) {
    s[i] = "[" + s[i] + "]";
  }

  return "data" + s.join("");
}

// custom plugins...
(function ($, window, document, undefined) {
  function SelectComponent($el) {
    var comboType = $("> .input-group-btn", $el),
      inputQ = $("> .input-query", $el),
      dataInput =
        comboType.find("a.dropdown-toggle").data("input") != null
          ? $(comboType.find("a.dropdown-toggle").data("input"), $el)
          : null,
      $types = {};

    return {
      init: function () {
        comboType.find("ul li a").each(function (i) {
          var $key = $(this).data("type");
          $types[$key] = [$(this).text(), $(this).data("placeholder") || null];
        });

        comboType.on("click", "ul li a", this.clickComboTypeItem.bind(this));

        if (dataInput && dataInput.val() != "") {
          this.chooseType(dataInput.val(), true);
        }
      },
      chooseType: function (type, nonfocus) {
        comboType.find("span.value").text($types[type][0]);
        inputQ.attr("placeholder", $types[type][1]);
        if (dataInput) dataInput.val(type);

        if (!nonfocus)
          setTimeout(function () {
            inputQ.focus();
          }, 300);
      },
      clickComboTypeItem: function (event) {
        this.chooseType($(event.target).data("type"));
        comboType.find("a.dropdown-toggle").dropdown("toggle");

        if (event) event.preventDefault();
        return false;
      },
    };
  }

  $.fn.selectComponent = function (options) {
    var theArguments = Array.prototype.slice.call(arguments);

    $(this).each(function () {
      var sco = false;
      if (!$.data(this, "selectComponent")) {
        $.data(this, "selectComponent", (sco = new SelectComponent(this)));
        sco.init(typeof options == "object" ? options : {});
      } else {
        sco = $.data(this, "selectComponent");
      }
      if (options && typeof options == "string" && sco[options]) {
        sco[options].apply(sco, theArguments.slice(1));
      }
    });
  };
})(jQuery, window, document);
