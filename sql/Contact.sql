create or replace procedure adaugare_formular_contact(p_nume varchar2,p_email varchar2,p_telefon varchar2,p_mesaj varchar2,p_raspuns in out varchar2)
as
    v_nr_maxim_formulare integer :=100;
    v_id integer;
    v_nr_formulare integer := 0;
begin
    select count(*) into v_nr_formulare from formulare_contact;
    
    if(v_nr_formulare>v_nr_maxim_formulare)then
        p_raspuns:='Numarul maxim de formulare in baza de date permis este atins';
        return;
    end if;
   
    --obtine id 
    select nvl(max(id),0)+1 into v_id from formulare_contact;
    
    insert into formulare_contact(id,nume,email,telefon,mesaj) values (v_id,p_nume,p_email,p_telefon,p_mesaj);
    
    p_raspuns:='OK';
end;
set serveroutput on;

declare
    v_raspuns varchar2(3500):='';
begin
    adaugare_formular_contact('remus','email','telefon','Salut,e ok site-ul vostru',v_raspuns);
    dbms_output.put_line(v_raspuns);
end;








select * from formulare_contact;


