function formRadix_checkForm(f){
	engine=f.engine[f.engine.selectedIndex].value
	if(!engine)alert('Selecione um serviço de busca.')
	if(engine=='Yahoo'){
		f.action='http://br.busca.yahoo.com/search/br'
		f.target='_blank'
		f.exp.name='p'
	}
	else if(engine=='Google'){
		f.action='http://www.google.com.br/search'	
		f.target='_blank'
		f.exp.name='q'
	}
	else{
		f.action=eval('radix_action'+engine)
		f.target='parceiros_radix'
		document.formRadix.title.value=document.formRadix.exp.value
		if(!isDef('radixWin')||radixWin.closed)radixWin=window.open('http://www.ibest.com.br/site/parceiros/radix.jsp','parceiros_radix')
		else radixWin.focus()
	}
}
