(function($){
  $(function(){

    $('.redBtn').on('click', () => {
      let article1 = $('#article');
      let selStr = article1.prop('selectionStart');
      let selEnd = article1.prop('selectionEnd');
      let v1 = article1.val().substr(0, selStr);
      let v2 = article1.val().substr(selStr, selEnd - selStr);
      let v3 = article1.val().substr(selEnd);

      let tagS = String("[red]");
      let tagE = String("[/red]");

      article1
        .val(v1 + tagS + v2 + tagE + v3)
        .prop({
          "selectionStart":selStr,
          "selectionEnd":selStr + v2.length + tagS.length + tagE.length
        })
        .trigger("focus");
    });

    $('.bluBtn').on('click', () => {
      let article1 = $('#article');
      let selStr = article1.prop('selectionStart');
      let selEnd = article1.prop('selectionEnd');
      let v1 = article1.val().substr(0, selStr);
      let v2 = article1.val().substr(selStr, selEnd - selStr);
      let v3 = article1.val().substr(selEnd);

      let tagS = String("[blu]");
      let tagE = String("[/blu]");

      article1
        .val(v1 + tagS + v2 + tagE + v3)
        .prop({
          "selectionStart":selStr,
          "selectionEnd":selStr + v2.length + tagS.length + tagE.length
        })
        .trigger("focus");
    });

    $('.greBtn').on('click', () => {
      let article1 = $('#article');
      let selStr = article1.prop('selectionStart');
      let selEnd = article1.prop('selectionEnd');
      let v1 = article1.val().substr(0, selStr);
      let v2 = article1.val().substr(selStr, selEnd - selStr);
      let v3 = article1.val().substr(selEnd);

      let tagS = String("[gre]");
      let tagE = String("[/gre]");

      article1
        .val(v1 + tagS + v2 + tagE + v3)
        .prop({
          "selectionStart":selStr,
          "selectionEnd":selStr + v2.length + tagS.length + tagE.length
        })
        .trigger("focus");
    });

    $('.ylwBtn').on('click', () => {
      let article1 = $('#article');
      let selStr = article1.prop('selectionStart');
      let selEnd = article1.prop('selectionEnd');
      let v1 = article1.val().substr(0, selStr);
      let v2 = article1.val().substr(selStr, selEnd - selStr);
      let v3 = article1.val().substr(selEnd);

      let tagS = String("[ylw]");
      let tagE = String("[/ylw]");

      article1
        .val(v1 + tagS + v2 + tagE + v3)
        .prop({
          "selectionStart":selStr,
          "selectionEnd":selStr + v2.length + tagS.length + tagE.length
        })
        .trigger("focus");
    });

    $('.whtBtn').on('click', () => {
      let article1 = $('#article');
      let selStr = article1.prop('selectionStart');
      let selEnd = article1.prop('selectionEnd');
      let v1 = article1.val().substr(0, selStr);
      let v2 = article1.val().substr(selStr, selEnd - selStr);
      let v3 = article1.val().substr(selEnd);

      let tagS = String("[wht]");
      let tagE = String("[/wht]");

      article1
        .val(v1 + tagS + v2 + tagE + v3)
        .prop({
          "selectionStart":selStr,
          "selectionEnd":selStr + v2.length + tagS.length + tagE.length
        })
        .trigger("focus");
    });

    $('.gryBtn').on('click', () => {
      let article1 = $('#article');
      let selStr = article1.prop('selectionStart');
      let selEnd = article1.prop('selectionEnd');
      let v1 = article1.val().substr(0, selStr);
      let v2 = article1.val().substr(selStr, selEnd - selStr);
      let v3 = article1.val().substr(selEnd);

      let tagS = String("[gry]");
      let tagE = String("[/gry]");

      article1
        .val(v1 + tagS + v2 + tagE + v3)
        .prop({
          "selectionStart":selStr,
          "selectionEnd":selStr + v2.length + tagS.length + tagE.length
        })
        .trigger("focus");
    });

    $('.qutBtn').on('click', () => {
      let article1 = $('#article');
      let selStr = article1.prop('selectionStart');
      let selEnd = article1.prop('selectionEnd');
      let v1 = article1.val().substr(0, selStr);
      let v2 = article1.val().substr(selStr, selEnd - selStr);
      let v3 = article1.val().substr(selEnd);

      let tagS = String("[qut]");
      let tagE = String("[/qut]");

      article1
        .val(v1 + tagS + v2 + tagE + v3)
        .prop({
          "selectionStart":selStr,
          "selectionEnd":selStr + v2.length + tagS.length + tagE.length
        })
        .trigger("focus");
    });

    $('.ancBtn').on('click', () => {
      let article1 = $('#article');
      let selStr = article1.prop('selectionStart');
      let selEnd = article1.prop('selectionEnd');
      let v1 = article1.val().substr(0, selStr);
      let v2 = article1.val().substr(selStr, selEnd - selStr);
      let v3 = article1.val().substr(selEnd);

      let tagS = String("[a]");
      let tagE = String("[/a]");

      article1
        .val(v1 + tagS + v2 + tagE + v3)
        .prop({
          "selectionStart":selStr,
          "selectionEnd":selStr + v2.length + tagS.length + tagE.length
        })
        .trigger("focus");
    });

    $('.bdoBtn').on('click', () => {
      let article1 = $('#article');
      let selStr = article1.prop('selectionStart');
      let selEnd = article1.prop('selectionEnd');
      let v1 = article1.val().substr(0, selStr);
      let v2 = article1.val().substr(selStr, selEnd - selStr);
      let v3 = article1.val().substr(selEnd);

      let tagS = String("[bdo]");
      let tagE = String("[/bdo]");

      article1
        .val(v1 + tagS + v2 + tagE + v3)
        .prop({
          "selectionStart":selStr,
          "selectionEnd":selStr + v2.length + tagS.length + tagE.length
        })
        .trigger("focus");
    });

    $('.bigBtn').on('click', () => {
      let article1 = $('#article');
      let selStr = article1.prop('selectionStart');
      let selEnd = article1.prop('selectionEnd');
      let v1 = article1.val().substr(0, selStr);
      let v2 = article1.val().substr(selStr, selEnd - selStr);
      let v3 = article1.val().substr(selEnd);

      let tagS = String("[big]");
      let tagE = String("[/big]");

      article1
        .val(v1 + tagS + v2 + tagE + v3)
        .prop({
          "selectionStart":selStr,
          "selectionEnd":selStr + v2.length + tagS.length + tagE.length
        })
        .trigger("focus");
    });

    $('.smlBtn').on('click', () => {
      let article1 = $('#article');
      let selStr = article1.prop('selectionStart');
      let selEnd = article1.prop('selectionEnd');
      let v1 = article1.val().substr(0, selStr);
      let v2 = article1.val().substr(selStr, selEnd - selStr);
      let v3 = article1.val().substr(selEnd);

      let tagS = String("[sml]");
      let tagE = String("[/sml]");

      article1
        .val(v1 + tagS + v2 + tagE + v3)
        .prop({
          "selectionStart":selStr,
          "selectionEnd":selStr + v2.length + tagS.length + tagE.length
        })
        .trigger("focus");
    });

    $('.hrzBtn').on('click', () => {
      let article1 = $('#article');
      let selStr = article1.prop('selectionStart');
      let selEnd = article1.prop('selectionEnd');
      let v1 = article1.val().substr(0, selStr);
      let v2 = article1.val().substr(selStr, selEnd - selStr);
      let v3 = article1.val().substr(selEnd);

      let tagS = String("[hrz]");

      article1
        .val(v1 + tagS + v2 + v3)
        .prop({
          "selectionStart":selStr,
          "selectionEnd":selStr + v2.length + tagS.length
        })
        .trigger("focus");
    });

  });
})(jQuery);