//Fun��o de mostrar o campo valor formatando-o--------------------------------
function mascara(o, f) {
	v_obj = o;
	v_fun = f;
	setTimeout("execmascara()", 1);
}

function execmascara() {
	v_obj.value = v_fun(v_obj.value);
}

function mreais(v) {
	v = v.replace(/\D/g, ""); // Remove tudo o que n�o � d�gito
	v = v.replace(/(\d{2})$/, ",$1"); // Coloca a virgula
	v = v.replace(/(\d+)(\d{3},\d{2})$/g, "$1.$2"); // Coloca o primeiro ponto
	return v;
}
function mrg(v){
    v=v.replace(/\D/g,"");                                      //Remove tudo o que n�o � d�gito
        v=v.replace(/(\d)(\d{7})$/,"$1.$2");    //Coloca o . antes dos �ltimos 3 d�gitos, e antes do verificador
        v=v.replace(/(\d)(\d{4})$/,"$1.$2");    //Coloca o . antes dos �ltimos 3 d�gitos, e antes do verificador
        v=v.replace(/(\d)(\d)$/,"$1-$2");               //Coloca o - antes do �ltimo d�gito
    return v;
}
function formatReal( data )
{
	var tmp = data+'';
	tmp = tmp.replace(/([0-9]{2})$/g, ",$1");
	if( tmp.length > 6 )
		tmp = tmp.replace(/([0-9]{3}),([0-9]{2}$)/g, ".$1,$2");
	return tmp;
}
function rdigit(v)
{
	v = v.replace(/[^\d]+/g,'');
	return v;
}
function mperc(v) {
	v = v.replace(/\D/g, ""); // Remove tudo o que n�o � d�gito
	v = v.replace(/(\d{2})$/, ".$1");
	if (v > 10) {
		v = 10;
	} // Impede valores acima de 100
	return v;
}

function Cep(v){
	v=v.replace(/D/g,"")                            
	v=v.replace(/^(\d{5})(\d)/,"$1-$2") 
	return v
}

function mpercent(e,q)
{
caixa=q.value
tecla=e.keyCode | e.which
ascii=String.fromCharCode(tecla)
regex=/\d/
r=regex.test(ascii)
if(r==true || tecla==0 || tecla==8)
{
switch(caixa.length)
{
case 2:{q.value+="."; break}	
case 4:{q.value+=ascii; break}	
}	
return true
}
else
{
return false	
}
}
function mint(v) {
	v = v.replace(/\D/g, ""); // Transforma tudo o que n�o � d�gito em ponto
	v = v.replace(/(\d{2})$/, ".$1");
	return v;
}// ---------------------------------------------------------------------------

function mcep(v){
    v=v.replace(/\D/g,"")                    //Remove tudo o que n�o � d�gito
    v=v.replace(/^(\d{5})(\d)/,"$1-$2")         //Esse � t�o f�cil que n�o merece explica��es
    return v
}
function mtel(v){
    v=v.replace(/\D/g,"");             //Remove tudo o que n�o � d�gito
    v=v.replace(/^(\d{2})(\d)/g,"($1) $2"); //Coloca par�nteses em volta dos dois primeiros d�gitos
    v=v.replace(/(\d)(\d{4})$/,"$1-$2");    //Coloca h�fen entre o quarto e o quinto d�gitos
    return v;
}
function cnpj(v){
    v=v.replace(/\D/g,"")                           //Remove tudo o que n�o � d�gito
    v=v.replace(/^(\d{2})(\d)/,"$1.$2")             //Coloca ponto entre o segundo e o terceiro d�gitos
    v=v.replace(/^(\d{2})\.(\d{3})(\d)/,"$1.$2.$3") //Coloca ponto entre o quinto e o sexto d�gitos
    v=v.replace(/\.(\d{3})(\d)/,".$1/$2")           //Coloca uma barra entre o oitavo e o nono d�gitos
    v=v.replace(/(\d{4})(\d)/,"$1-$2")              //Coloca um h�fen depois do bloco de quatro d�gitos
    return v
}
function mcpf(v){
    v=v.replace(/\D/g,"")                    //Remove tudo o que n�o � d�gito
    v=v.replace(/(\d{3})(\d)/,"$1.$2")       //Coloca um ponto entre o terceiro e o quarto d�gitos
    v=v.replace(/(\d{3})(\d)/,"$1.$2")       //Coloca um ponto entre o terceiro e o quarto d�gitos
                                             //de novo (para o segundo bloco de n�meros)
    v=v.replace(/(\d{3})(\d{1,2})$/,"$1-$2") //Coloca um h�fen entre o terceiro e o quarto d�gitos
    return v
}
function mdata(v){
    v=v.replace(/\D/g,"");                    //Remove tudo o que n�o � d�gito
    v=v.replace(/(\d{2})(\d)/,"$1/$2");
    v=v.replace(/(\d{2})(\d)/,"$1/$2");
 
    v=v.replace(/(\d{2})(\d{2})$/,"$1$2");
    return v;
}
function mtempo(v){
    v=v.replace(/\D/g,"");                    //Remove tudo o que n�o � d�gito
    v=v.replace(/(\d{1})(\d{2})(\d{2})/,"$1:$2.$3");
    return v;
}
function mhora(v){
    v=v.replace(/\D/g,"");                    //Remove tudo o que n�o � d�gito
    v=v.replace(/(\d{2})(\d)/,"$1h$2");
    return v;
}
function mnum(v){
    v=v.replace(/\D/g,"");                                      //Remove tudo o que n�o � d�gito
    return v;
}
function mvalor(v){
    v=v.replace(/\D/g,"");//Remove tudo o que n�o � d�gito
    v=v.replace(/(\d)(\d{8})$/,"$1.$2");//coloca o ponto dos milh�es
    v=v.replace(/(\d)(\d{5})$/,"$1.$2");//coloca o ponto dos milhares
 
    v=v.replace(/(\d)(\d{2})$/,"$1,$2");//coloca a virgula antes dos 2 �ltimos d�gitos
    return v;
}