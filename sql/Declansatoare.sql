---------trigger pentru creare log-uri  la tabelul utilizatori
create or replace trigger actualizare_utilizatori
before insert or update or delete on utilizatori
for each row
declare
    v_fisier utl_file.file_type;
begin
    v_fisier:=utl_file.fopen('MYDIR','logs.txt','A',10000);
    case
        when updating then
            if(:OLD.nume_utilizator!=:NEW.nume_utilizator)then
                utl_file.put_line(v_fisier,sysdate||' : Utilizatorul cu id '||:OLD.id||' si cu numele '||:OLD.nume_utilizator||' isi modifica numele in '||:NEW.nume_utilizator);
            elsif(:OLD.parola!=:NEW.parola) then
                utl_file.put_line(v_fisier,sysdate||' : Utilizatorul cu id '||:OLD.id||' si cu numele '||:OLD.nume_utilizator||' isi modifica parola din '||:OLD.parola||' in '||:NEW.parola);
            elsif(:OLD.email!=:NEW.email) then
                utl_file.put_line(v_fisier,sysdate||' : Utilizatorul cu id '||:OLD.id||' si cu numele '||:OLD.nume_utilizator||' isi modifica email din '||:OLD.email||' in '||:NEW.email);
            elsif(:OLD.telefon!=:NEW.telefon) then
                utl_file.put_line(v_fisier,sysdate||' : Utilizatorul cu id '||:OLD.id||' si cu numele '||:OLD.nume_utilizator||' isi modifica numarul de telefon din '||:OLD.telefon||' in '||:NEW.telefon);
            end if;
        when inserting then
            utl_file.put_line(v_fisier,sysdate||' : Un nou utilizator a fost creat cu urmatoarele atribute: id='||:new.id||' , nume_utilizator='||:new.nume_utilizator||' , parola='||:new.parola||' , email='||:new.email||' , telefon='||:new.telefon);
        when deleting then
            utl_file.put_line(v_fisier,sysdate||' : Un utilizator cu atributele id='||:old.id||' , nume_utilizator='||:old.nume_utilizator||' , parola='||:old.parola||' , email='||:old.email||' , telefon='||:old.telefon||' a fost sters');
    end case;
    
    utl_file.fclose(v_fisier);
end;

----trigger pentru creare log-uri la tabelul descrieri
create or replace trigger declansator_descrieri
before insert or update or delete on descrieri
for each row
declare
    v_fisier utl_file.file_type;
begin
    v_fisier:=utl_file.fopen('MYDIR','logs.txt','A',10000);
    case
        when updating then
            utl_file.put_line(v_fisier,sysdate||' : Descrierea cu datele vechi id='||:old.id||' , descriere='||:old.descriere||' , limba='||:old.limba||' sa schimbat in id='||:new.id||' , descriere='||:new.descriere||' , limba='||:new.limba);
        when inserting then
            utl_file.put_line(v_fisier,sysdate||' : O noua descriere a fost adaugata cu datele id='||:new.id||' , descriere='||:new.descriere||' , limba='||:new.limba);
        when deleting then
            utl_file.put_line(v_fisier,sysdate||' : O descriere cu datele id='||:old.id||' , descriere='||:old.descriere||' , limba='||:old.limba||' a fost stearsa');
    end case;
    
    utl_file.fclose(v_fisier);
end;


----trigger pentru creare log-uri la tabelul salvari
create or replace trigger declansator_salvari
before insert or delete on salvari
for each row
declare
    v_fisier utl_file.file_type;
begin
    v_fisier:=utl_file.fopen('MYDIR','logs.txt','A',10000);
    case
        when inserting then
            utl_file.put_line(v_fisier,sysdate||' : O noua salvare a fost adaugata.Utilizatorul cu id='||:new.id_utilizator||' a salvat pentru contul sau animalul cu id='||:new.id_animal);
        when deleting then
            utl_file.put_line(v_fisier,sysdate||' : O salvare a fost stearsa.Salvarea era pentru utilizatorul cu id='||:old.id_utilizator||' avand animalul cu id='||:old.id_animal);
    end case;
    
    utl_file.fclose(v_fisier);
end;
