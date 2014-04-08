
function permite(elEvento, permitidos) {
// Variables que definen los caracteres permitidos

var numeros = "0123456789.,";
var caracteres = " abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZ-/#*@()&$";
var numeros_caracteres = numeros + caracteres;
var teclas_especiales = [8, 37, 39, 46, 13, 9];
// 8 = BackSpace, 46 = Supr, 37 = flecha izquierda, 39 = flecha derecha
// Seleccionar los caracteres a partir del parámetro de la función
  switch(permitidos) {
    case 'num':
    permitidos = numeros;
    break;
    case 'car':
    permitidos = caracteres;
    break;
    case 'num_car':
    permitidos = numeros_caracteres;
    break;
}
// Obtener la tecla pulsada
var evento = elEvento || window.event;
var codigoCaracter = evento.charCode || evento.keyCode;
var caracter = String.fromCharCode(codigoCaracter);
// Comprobar si la tecla pulsada es alguna de las teclas especiales
// (teclas de borrado y flechas horizontales)
var tecla_especial = false;
for(var i in teclas_especiales) {
    if(codigoCaracter == teclas_especiales[i]) {
    tecla_especial = true;
    break;
  }
}
// Comprobar si la tecla pulsada se encuentra en los caracteres permitidos
// o si es una tecla especial
return permitidos.indexOf(caracter) != -1 || tecla_especial;
}

//Funcion que nos permite escribir una fecha 
//de una manera rapida
function formafecha(campo)
{
	if (campo.value.length==2 || campo.value.length==5)
	{	
		campo.value = campo.value+"/";
		return false;
	}
}

//Funcion que elimina los espacios en blaco o saltos de linea
//al principio de una cadena
function ltrim(s) {
	return s.replace(/^\s+/, "");
}

//Funcion que elimina los espacios en blaco o saltos de linea
//al final de una cadena
function rtrim(s) {
	return s.replace(/\s+$/, "");
}

//Funcion que elimina los espacios en blanco o saltos de linea
//al comienzo y al final de una cadena
function trim(s) {
	return rtrim(ltrim(s));
}

//Funcion que permite, que cuando se preciona enter se vaya
//al siguien campo de texto del formulario
function handleEnter(field, event) {

	var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;
			if (keyCode == 13) {
				var i;
				for (i = 0; i < field.form.elements.length; i++)
					if (field == field.form.elements[i])
						break;
				i = (i + 1) % field.form.elements.length;
				field.form.elements[i].focus();
				return false;
			} 
			else
			return true;
		}

function msg(text)
{
    str = '<p style="margin-top:10px"><span class="ui-icon ui-icon-alert" style="float: left; margin: 0pt 7px 50px 0pt;"></span>'+text+'</p>';
    $("#msgdialog").empty().append(str);
    $("#dialog").dialog("open");
}

function msgok(text)
{
  str = '<p style="margin-top:10px"><span class="ui-icon ui-icon-check" style="float: left; margin: 0pt 7px 50px 0pt;"></span>'+text+'</p>';
    $("#msgdialog").empty().append(str);
    $("#dialog").dialog("open");
}

function msgerror(text)
{
    str = '<p style="margin-top:10px"><span class="ui-icon ui-icon-closethick" style="float: left; margin: 0pt 7px 50px 0pt;"></span>'+text+'</p>';
    $("#msgdialog").empty().append(str);
    $("#dialog").dialog("open");

}
function popup(url,width,height){cuteLittleWindow = window.open(url,"littleWindow","location=no,width="+width+",height="+height+",top=80,left=300,scrollbars=yes"); }

function showboxmsg(text,tipo)
   {
       //@tipo => 1 ok, 2 fail, 3 alert
       var html = "";
       $('.box-msg').css('display','none');
       switch(tipo)
       {
           case 1 : 
                    html = '<p><span class="ui-icon ui-icon-check" style="float: left; margin-right: .3em;"></span>';                    
                        html += text+'</p>';				
                    $('.box-msg').removeClass('ui-state-error-adz');
                    $('.box-msg').addClass('ui-state-active-adz');
                    $('.box-msg').empty().append(html);
                    break;
           case 2 : 
                    html = '<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>';                    
                    html += text+'</p>';
                    $('.box-msg').removeClass('ui-state-active-adz');                    
                    $('.box-msg').addClass('ui-state-error-adz');
                    $('.box-msg').empty().append(html);
                    break;
           case 3 : 
                    html = '<p><span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>';                    
                    html += text+'</p>';
                     $('.box-msg').removeClass('ui-state-error-adz');
                    $('.box-msg').addClass('ui-state-active-adz');
                    $('.box-msg').empty().append(html);
                    break;
           default: break;
       }
       $('.box-msg').show('pulsate',200);
   }
   
   function hideboxmsg()
   {
       $('.box-msg').fadeOut();
   }
   
   function esrucok(ruc)
   {
      return (!( esnulo(ruc) || !esnumero(ruc) || !eslongrucok(ruc) || !valruc(ruc) ));
   }
   function esnulo(campo){ return (campo == null||campo=="");}
   function esnumero(campo){ return (!(isNaN( campo )));}
   function eslongrucok(ruc){return ( ruc.length == 11 );}
   function valruc(valor)
   {
      valor = trim(valor)
      if ( esnumero( valor ) ) {
        if ( valor.length == 8 ){
          suma = 0
          for (i=0; i<valor.length-1;i++){
            digito = valor.charAt(i) - '0';
            if ( i==0 ) suma += (digito*2)
            else suma += (digito*(valor.length-i))
          }
          resto = suma % 11;
          if ( resto == 1) resto = 11;
          if ( resto + ( valor.charAt( valor.length-1 ) - '0' ) == 11 ){
            return true
          }
        } else if ( valor.length == 11 ){
          suma = 0
          x = 6
          for (i=0; i<valor.length-1;i++){
            if ( i == 4 ) x = 8
            digito = valor.charAt(i) - '0';
            x--
            if ( i==0 ) suma += (digito*x)
            else suma += (digito*x)
          }
          resto = suma % 11;
          resto = 11 - resto
          
          if ( resto >= 10) resto = resto - 10;
          if ( resto == valor.charAt( valor.length-1 ) - '0' ){
            return true
          }      
        }
      }
      return false
    }


function json_encode (mixed_val) {
  // http://kevin.vanzonneveld.net
  // +      original by: Public Domain (http://www.json.org/json2.js)
  // + reimplemented by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
  // +      improved by: Michael White
  // +      input by: felix
  // +      bugfixed by: Brett Zamir (http://brett-zamir.me)
  // *        example 1: json_encode(['e', {pluribus: 'unum'}]);
  // *        returns 1: '[\n    "e",\n    {\n    "pluribus": "unum"\n}\n]'
/*
    http://www.JSON.org/json2.js
    2008-11-19
    Public Domain.
    NO WARRANTY EXPRESSED OR IMPLIED. USE AT YOUR OWN RISK.
    See http://www.JSON.org/js.html
  */
  var retVal, json = this.window.JSON;
  try {
    if (typeof json === 'object' && typeof json.stringify === 'function') {
      retVal = json.stringify(mixed_val); // Errors will not be caught here if our own equivalent to resource
      //  (an instance of PHPJS_Resource) is used
      if (retVal === undefined) {
        throw new SyntaxError('json_encode');
      }
      return retVal;
    }

    var value = mixed_val;

    var quote = function (string) {
      var escapable = /[\\\"\u0000-\u001f\u007f-\u009f\u00ad\u0600-\u0604\u070f\u17b4\u17b5\u200c-\u200f\u2028-\u202f\u2060-\u206f\ufeff\ufff0-\uffff]/g;
      var meta = { // table of character substitutions
        '\b': '\\b',
        '\t': '\\t',
        '\n': '\\n',
        '\f': '\\f',
        '\r': '\\r',
        '"': '\\"',
        '\\': '\\\\'
      };

      escapable.lastIndex = 0;
      return escapable.test(string) ? '"' + string.replace(escapable, function (a) {
        var c = meta[a];
        return typeof c === 'string' ? c : '\\u' + ('0000' + a.charCodeAt(0).toString(16)).slice(-4);
      }) + '"' : '"' + string + '"';
    };

    var str = function (key, holder) {
      var gap = '';
      var indent = '    ';
      var i = 0; // The loop counter.
      var k = ''; // The member key.
      var v = ''; // The member value.
      var length = 0;
      var mind = gap;
      var partial = [];
      var value = holder[key];

      // If the value has a toJSON method, call it to obtain a replacement value.
      if (value && typeof value === 'object' && typeof value.toJSON === 'function') {
        value = value.toJSON(key);
      }

      // What happens next depends on the value's type.
      switch (typeof value) {
      case 'string':
        return quote(value);

      case 'number':
        // JSON numbers must be finite. Encode non-finite numbers as null.
        return isFinite(value) ? String(value) : 'null';

      case 'boolean':
      case 'null':
        // If the value is a boolean or null, convert it to a string. Note:
        // typeof null does not produce 'null'. The case is included here in
        // the remote chance that this gets fixed someday.
        return String(value);

      case 'object':
        // If the type is 'object', we might be dealing with an object or an array or
        // null.
        // Due to a specification blunder in ECMAScript, typeof null is 'object',
        // so watch out for that case.
        if (!value) {
          return 'null';
        }
        if ((this.PHPJS_Resource && value instanceof this.PHPJS_Resource) || (window.PHPJS_Resource && value instanceof window.PHPJS_Resource)) {
          throw new SyntaxError('json_encode');
        }

        // Make an array to hold the partial results of stringifying this object value.
        gap += indent;
        partial = [];

        // Is the value an array?
        if (Object.prototype.toString.apply(value) === '[object Array]') {
          // The value is an array. Stringify every element. Use null as a placeholder
          // for non-JSON values.
          length = value.length;
          for (i = 0; i < length; i += 1) {
            partial[i] = str(i, value) || 'null';
          }

          // Join all of the elements together, separated with commas, and wrap them in
          // brackets.
          v = partial.length === 0 ? '[]' : gap ? '[\n' + gap + partial.join(',\n' + gap) + '\n' + mind + ']' : '[' + partial.join(',') + ']';
          gap = mind;
          return v;
        }

        // Iterate through all of the keys in the object.
        for (k in value) {
          if (Object.hasOwnProperty.call(value, k)) {
            v = str(k, value);
            if (v) {
              partial.push(quote(k) + (gap ? ': ' : ':') + v);
            }
          }
        }

        // Join all of the member texts together, separated with commas,
        // and wrap them in braces.
        v = partial.length === 0 ? '{}' : gap ? '{\n' + gap + partial.join(',\n' + gap) + '\n' + mind + '}' : '{' + partial.join(',') + '}';
        gap = mind;
        return v;
      case 'undefined':
        // Fall-through
      case 'function':
        // Fall-through
      default:
        throw new SyntaxError('json_encode');
      }
    };

    // Make a fake root object containing our value under the key of ''.
    // Return the result of stringifying the value.
    return str('', {
      '': value
    });

  } catch (err) { // Todo: ensure error handling above throws a SyntaxError in all cases where it could
    // (i.e., when the JSON global is not available and there is an error)
    if (!(err instanceof SyntaxError)) {
      throw new Error('Unexpected error type in json_encode()');
    }
    this.php_js = this.php_js || {};
    this.php_js.last_error_json = 4; // usable by json_last_error()
    return null;
  }
}    

function clone(from) 
{
  if(from == null || typeof from != "object")
    return from;
  if(from.constructor != Object &&
     from.constructor != Array)
    return from;
  if(from.constructor == Date ||
     from.constructor == RegExp ||
     from.constructor == Function ||
     from.constructor == String ||
     from.constructor == Number ||
     from.constructor == Boolean)
    return new from.constructor(from);
  var to = {};
  to = to || new from.constructor();
  for (var name in from) {
    to[name] = typeof to[name] == "undefined" ?
      this.clone(from[name]) :
      to[name];
  }
  return to;

}

Array.prototype.clear = function() {
  while (this.length > 0) {
    this.pop();
  }
};

function UpdateFecha(fecha,d)
{

  fecha = fecha.split("/");
  var Anio =fecha[2];
  var Mes =fecha[1];
  var Dia =fecha[0];  
  if(isNaN(d) || d=="")
    d=0;
    Dia = parseInt(Dia) + parseInt(d);
    Mes = parseInt(Mes) - parseInt(1);
    var Fecha =new Date(Anio,Mes,Dia);
    Anio = Fecha.getFullYear();
    Mes = Fecha.getMonth()+1;
    Dia = Fecha.getDate();
    if (Dia<10)Dia='0'+Dia;
    if (Mes<10)Mes='0'+Mes;
    nfecha = Dia+'/'+Mes+'/'+Anio;
    return nfecha;
}

function finMes(nMes, nAno)
{ 
  var aFinMes = new Array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31); 
  return aFinMes[nMes - 1] + (((nMes == 2) && (nAno % 4) == 0)? 1: 0); 
}