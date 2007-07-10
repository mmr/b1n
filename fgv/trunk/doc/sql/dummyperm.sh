#!/bin/sh

# Criando o Grupo
cat << shub
INSERT INTO grupo (grp_id, grp_nome) VALUES (666, 'Grupo do Tio Binary');
shub

# Dando permissoes ao Grupo
i=0
while [ $i -le ${1:-248} ]
do
    echo "INSERT INTO grp_fnc (grp_id, fnc_id) VALUES (666, $i);"
    i=$(($i+1))
done

# Inserindo Usuarios no Grupo
cat << shub
INSERT INTO grp_mem (grp_id, mem_id) VALUES (666, 1); 
INSERT INTO grp_mem (grp_id, mem_id) VALUES (666, 2); 
INSERT INTO grp_mem (grp_id, mem_id) VALUES (666, 3); 
shub
