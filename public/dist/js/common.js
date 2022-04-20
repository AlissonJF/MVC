BASE='https://appserver.unimar.br/intranet';

var browsers = ["Opera", "Edg", "Chrome", "Safari", "Firefox", "MSIE", "Trident"];
var userbrowser, useragent = navigator.userAgent;
for (var i = 0; i < browsers.length; i++) {
    if( useragent.indexOf(browsers[i]) > -1 ) {
        userbrowser = browsers[i];
        break;
    }
};

switch(userbrowser) {
    case 'MSIE':
        userbrowser = 'Internet Explorer';
        break;

    case 'Trident':
        userbrowser = 'Internet Explorer';
        break;

}

if(userbrowser == 'Internet Explorer'){
   // alert("Este Navegador não suporta o Sistema de Requisições, utilize outro navegador");
    window.location.href = BASE+"/explorerErro/";
}


    function alertPermissao(){
      toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": false,
            "progressBar": false,
            "positionClass": "toast-top-center",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        }

        toastr.error("Você não tem Permissão neste item do Menu!");

    }


function preload(obj){
    $(obj).block({
        message: '<img src="'+BASE+'/public/images/pre2.gif" height="100" alt=""></a>',
        css: { border: '0',  backgroundColor:'' }
    });
}

function unblock(obj){
    $(obj).unblock();
}

function b64EncodeUnicode(str) {
  // first we use encodeURIComponent to get percent-encoded UTF-8,
  // then we convert the percent encodings into raw bytes which
  // can be fed into btoa.
  return btoa(encodeURIComponent(str).replace(/%([0-9A-F]{2})/g,
    function toSolidBytes(match, p1) {
      return String.fromCharCode('0x' + p1);
    }));
}


function b64DecodeUnicode(str) {
  // Going backwards: from bytestream, to percent-encoding, to original string.
  return decodeURIComponent(atob(str).split('').map(function(c) {
    return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
  }).join(''));
}

function montarOptions(objectArr, selected = null) {
  let html = '';
  if (selected) {
    html = '<option disabled>SELECIONE UMA OPÇÃO</option>';
  } else {
    html = '<option selected disabled>SELECIONE UMA OPÇÃO</option>';
  }
  objectArr.forEach(o => {
    objKey = Object.values(o)[0];
    objVal = Object.values(o)[1];
    if (selected && selected == objKey) {
      html += `<option selected value="${objKey}">${objVal}</option>`;
    } else {
      html += `<option value="${objKey}">${objVal}</option>`;
    }
  });
  return html;
}
