import "./jquery/leak_jquery";
import "./jquery/jquery-migrate-1.4.1.min";
import "./fancybox2/jquery.fancybox";
import "jquery-mask-plugin";
import "jquery-validation";

import $ from "jquery";

// import { $init as $podcasts } from "./podcasts";

window.defaultUrl = "./";

function loadScript(src, callback) {
  var s, r, t;
  r = false;
  s = document.createElement("script");
  s.type = "text/javascript";
  s.src = src;
  s.onload = s.onreadystatechange = function () {
    if (!r && (!this.readyState || this.readyState == "complete")) {
      r = true;
      if (!!callback) callback();
    }
  };
  t = document.getElementsByTagName("script")[0];
  t.parentNode.insertBefore(s, t);
}
window.loadScript = loadScript;

function renderIf(query) {
  if (typeof query === "string") query = $(query);

  const dfd = jQuery.Deferred();
  if (query !== null && !!query.length) {
    dfd.resolve(query, query[0] || null);
  } else {
    dfd.reject();
  }

  return dfd.promise();
}
window.renderIf = renderIf;

function showLoading() {
  $("#activity").removeClass("disabled");
}
window.showLoading = showLoading;

function hideLoading() {
  $("#activity").addClass("disabled");
}
window.hideLoading = hideLoading;

function viewport() {
  var e = window,
    a = "inner";
  if (!("innerWidth" in window)) {
    a = "client";
    e = document.documentElement || document.body;
  }
  return { width: e[a + "Width"], height: e[a + "Height"] };
}
window.viewport = viewport;

// Returns a function, that, when invoked, will only be triggered at most once
// during a given window of time.
// from: https://stackoverflow.com/a/27078401/1683407
function throttle(func, wait, options) {
  var context, args, result;
  var timeout = null;
  var previous = 0;
  if (!options) options = {};
  var later = function () {
    previous = options.leading === false ? 0 : Date.now();
    timeout = null;
    result = func.apply(context, args);
    if (!timeout) context = args = null;
  };
  return function () {
    var now = Date.now();
    if (!previous && options.leading === false) previous = now;
    var remaining = wait - (now - previous);
    context = this;
    args = arguments;
    if (remaining <= 0 || remaining > wait) {
      if (timeout) {
        clearTimeout(timeout);
        timeout = null;
      }
      previous = now;
      result = func.apply(context, args);
      if (!timeout) context = args = null;
    } else if (!timeout && options.trailing !== false) {
      timeout = setTimeout(later, remaining);
    }
    return result;
  };
}
window.throttle = throttle;

function registerFormMaskUpdater(event) {
  $("input[data-jmask]").each((_, i) => {
    const input = $(i);
    if (!!input.data("jmask-applied")) {
      input.unmask();
    }

    if (input.data("jmask") !== "phone") {
      input.mask(input.data("jmask")).data("jmask-applied", true);
    } else {
      input
        .mask("(99) 99999-9999", {
          onKeyPress: (phone, e, field, options) => {
            const masks = ["(99) 9999-9999", "(99) 99999-9999"];
            const testPhone = !!phone && phone.indexOf(")") !== -1 ? phone.substr(phone.indexOf(")") + 2, 1) : "";
            const mask = testPhone === "9" ? masks[1] : masks[0];
            field.mask(mask, options);
          },
        })
        .data("jmask-applied", true);
    }
  });
}

function enableSubmitBtn() {
  $("form input[type=submit]").removeAttr("disabled").removeClass("disabled");
  $("form button[type=submit]").removeAttr("disabled").removeClass("disabled");
}
window.enableSubmitBtn = enableSubmitBtn;

function arrangeForm() {
  if ($.fn.mask) {
    $(document.body).off("DOMchange", registerFormMaskUpdater);
    $(document.body).on("DOMchange", registerFormMaskUpdater);
  }

  if ($.fn.validate) {
    const _clearErrorTag = element => {
      $(element).parents("div.input").find("div.form-error:visible").addClass("help-block");
      $(element).parents("div.input").find("div.error-message").remove();
    };

    $.extend($.validator.messages, {
      required: "Este campo &eacute; requerido.",
      remote: "Por favor, corrija este campo.",
      email: "Por favor, forne&ccedil;a um endere&ccedil;o eletr&ocirc;nico v&aacute;lido.",
      url: "Por favor, forne&ccedil;a uma URL v&aacute;lida.",
      date: "Por favor, forne&ccedil;a uma data v&aacute;lida.",
      dateISO: "Por favor, forne&ccedil;a uma data v&aacute;lida (ISO).",
      number: "Por favor, forne&ccedil;a um n&uacute;mero v&aacute;lido.",
      digits: "Por favor, forne&ccedil;a somente d&iacute;gitos.",
      creditcard: "Por favor, forne&ccedil;a um cart&atilde;o de cr&eacute;dito v&aacute;lido.",
      equalTo: "Por favor, forne&ccedil;a o mesmo valor novamente.",
      accept: "Por favor, forne&ccedil;a um valor com uma extens&atilde;o v&aacute;lida.",
      maxlength: $.validator.format("Por favor, forne&ccedil;a n&atilde;o mais que {0} caracteres."),
      minlength: $.validator.format("Por favor, forne&ccedil;a ao menos {0} caracteres."),
      rangelength: $.validator.format("Por favor, forne&ccedil;a um valor entre {0} e {1} caracteres de comprimento."),
      range: $.validator.format("Por favor, forne&ccedil;a um valor entre {0} e {1}."),
      max: $.validator.format("Por favor, forne&ccedil;a um valor menor ou igual a {0}."),
      min: $.validator.format("Por favor, forne&ccedil;a um valor maior ou igual a {0}."),
    });

    $.validator.addMethod(
      "cpf",
      function (d, c) {
        if ((d = d.replace(/[^\d]/g, "").split("")).length != 11) {
          return this.optional(c) || false;
        }
        if (new RegExp("^" + d[0] + "{11}$").test(d.join(""))) {
          return this.optional(c) || false;
        }
        for (var f = 10, g = 0, e = 0; f >= 2; g += d[e++] * f--) {}
        if (d[9] != ((g %= 11) < 2 ? 0 : 11 - g)) {
          return this.optional(c) || false;
        }
        for (var f = 11, g = 0, e = 0; f >= 2; g += d[e++] * f--) {}
        if (d[10] != ((g %= 11) < 2 ? 0 : 11 - g)) {
          return this.optional(c) || false;
        }
        return this.optional(c) || true;
      },
      "CPF inválido."
    );

    $.validator.addMethod(
      "cnpj",
      function (e, h) {
        var d = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];
        if ((e = e.replace(/[^\d]/g, "").split("")).length != 14) {
          return this.optional(h) || false;
        }
        for (var f = 0, g = 0; f < 12; g += e[f] * d[++f]) {}
        if (e[12] != ((g %= 11) < 2 ? 0 : 11 - g)) {
          return this.optional(h) || false;
        }
        for (var f = 0, g = 0; f <= 12; g += e[f] * d[f++]) {}
        if (e[13] != ((g %= 11) < 2 ? 0 : 11 - g)) {
          return this.optional(h) || false;
        }
        return this.optional(h) || true;
      },
      "CNPJ inválido."
    );

    $.validator.setDefaults({
      errorClass: "form-error",
      errorElement: "div",
      errorPlacement: function (error, element) {
        if (element.hasClass("select-styled")) {
          error.appendTo(element.closest(".select"));
        } else {
          error.appendTo(element.closest(".input"));
        }
      },
      highlight: (element, errorClass, validClass) => {
        if (element.type === "radio") {
          this.findByName(element.name)
            .addClass(errorClass)
            .removeClass(validClass)
            .parents("div.input")
            .addClass("has-error");
        } else if (element.type === "select-one") {
          $(element).addClass(errorClass).removeClass(validClass).parents("div.select").addClass("has-error");
        } else {
          $(element).addClass(errorClass).removeClass(validClass).parents("div.input").addClass("has-error");
        }

        setTimeout(() => _clearErrorTag(element), 10);
      },
      unhighlight: (element, errorClass, validClass) => {
        if (element.type === "radio") {
          this.findByName(element.name)
            .removeClass(errorClass)
            .addClass(validClass)
            .parents("div.input")
            .removeClass("has-error");
        } else if (element.type === "select-one") {
          $(element).removeClass(errorClass).addClass(validClass).parents("div.select").removeClass("has-error");
        } else {
          $(element).removeClass(errorClass).addClass(validClass).parents("div.input").removeClass("has-error");
        }

        setTimeout(() => _clearErrorTag(element), 10);
      },
    });

    if ($("form[data-validate]").length) {
      $("form[data-validate]").each((_, form) => {
        $(form).removeAttr("data-validate").validate();
      });
    }
  }

  $("form div.input.error").removeClass("error").addClass("has-error");

  $("form div.input div.error-message").addClass("help-block");

  $(document.body).trigger("DOMchange");
}

function openHeaderNav() {
  if (!!__closeHeaderNavTimer) {
    clearTimeout(__closeHeaderNavTimer);
  }

  $("#header-nav-overlay").removeClass("hidden").addClass("fixed opacity-0");
  $("#header-nav").removeClass("hidden").addClass("fixed -translate-x-full");

  requestAnimationFrame(() => {
    $("#header-nav-overlay").addClass("opacity-100");
    $("#header-nav").removeClass("-translate-x-full").addClass("translate-x-0");
    $(document.body).addClass("overflow-hidden");
  });
}

function closeHeaderNav() {
  if (!!__closeHeaderNavTimer) {
    clearTimeout(__closeHeaderNavTimer);
  }

  $("#header-nav-overlay").removeClass("opacity-100");
  $("#header-nav").removeClass("translate-x-0").addClass("-translate-x-full");
  $(document.body).removeClass("overflow-hidden");

  __closeHeaderNavTimer = setTimeout(() => {
    $("#header-nav-overlay").removeClass("fixed").addClass("hidden");
    $("#header-nav").removeClass("fixed").addClass("hidden");
  }, 250);
}

function $init() {
  for (var i = 0, a = document.links; i < a.length; i++) if (a[i].rel && a[i].rel == "external") a[i].target = "_blank";

  if (!window.defaultUrl || window.defaultUrl === "./") {
    window.defaultUrl = $(document.body).data("uri");
  }

  if ($("#flashMessage").length > 0) {
    $("#flashMessage").hide().slideDown("normal");
    setTimeout('$("#flashMessage").trigger("click");', 8000);
  }

  if ($("#authMessage").length > 0) {
    $("#authMessage").hide().slideDown("normal");
    setTimeout('$("#authMessage").trigger("click");', 8000);
  }

  if (
    !(navigator.userAgent.match(/iPhone/i) || navigator.userAgent.match(/iPod/i) || navigator.userAgent.match(/iPad/i))
  ) {
    //normal...
  } else {
    $(document.body).addClass("ios");
  }

  $(document).on("click", "#toggle-nav", event => {
    event.preventDefault();
    openHeaderNav();
  });

  $(document).on("click", "#header-nav-overlay, #header-nav .close", event => {
    event.preventDefault();
    closeHeaderNav();
  });

  /* LOCAL STORAGE COOKIES MESSAGE */
  if (!localStorage.getItem("JavaScriptCookies")) {
    $(".shadow-cookies").removeClass("hidden");
  }

  function acceptCookies() {
    $(".shadow-cookies").addClass("hidden");
    localStorage.setItem("JavaScriptCookies", "accept");
  }

  $("#btn-cookies").on("click", function (event) {
    event.preventDefault();
    acceptCookies();
  });

  $(document)
    .ajaxStart(window.showLoading)
    .ajaxComplete(window.hideLoading)
    .on("click", "#flashMessage", function (event) {
      event.preventDefault();
      $("#flashMessage").stop().slideUp("normal");

      return false;
    })
    .on("click", "#authMessage", function (event) {
      event.preventDefault();
      $("#authMessage").stop().slideUp("normal");

      return false;
    });

  if ($.fn.fancybox) {
    $(document).on("click", "main a[data-fancybox]", function (event) {
      event.preventDefault();

      var $a = $(event.currentTarget || event.target || this);

      var images = [];
      var index = 0;

      var g = $a.data("fancybox");
      if (!!g) {
        $("main a[data-fancybox='" + g + "']").each(function (i, el) {
          if ($(el).is($a)) {
            index = i;
          }

          images.push({ href: $(el).attr("href"), title: $(el).attr("title") || "" });
        });
      } else {
        images = [{ href: $a.attr("href"), title: $a.attr("title") || "" }];
      }

      requestAnimationFrame(function () {
        $.fancybox(images, {
          parent: "body", // turbolinks POG
          index: index,
          openEffect: "elastic",
          openSpeed: 150,
          closeEffect: "elastic",
          closeSpeed: 150,
          padding: 10,
        });
      });

      return false;
    });
  }

  arrangeForm();

  // start custom sections
  // $podcasts();
}

$($init);
