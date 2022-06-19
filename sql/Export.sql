set serveroutput on;
/*
Sa se construiasca o procedura PLSQL avand ca parametru numele unei tabele si 
care sa exporte intr-un fisier aflat in format SQL toate liniile tabelei respective.

La inceputul fisierului va fi scrisa si o comanda de drop table respectiv una 
de creare a tabelei. Tipurile datelor vor fi aceleasi ca cele din tabelul initial;

Se vor scrie comenzile de tip insert care vor popula tabela creata (cu datele 
din tabela initiala, cea a carui nume a fost dat ca parametru).
*/
-------------------------------------------------
CREATE OR REPLACE FUNCTION getType(v_rec_tab DBMS_SQL.DESC_TAB, v_nr_col int) RETURN VARCHAR2 AS
  v_tip_coloana VARCHAR2(200);
  v_precizie VARCHAR2(40);
BEGIN
     CASE (v_rec_tab(v_nr_col).col_type)
        WHEN 1 THEN v_tip_coloana := 'VARCHAR2'; v_precizie := '(' || v_rec_tab(v_nr_col).col_max_len || ')';
        WHEN 2 THEN v_tip_coloana := 'NUMBER'; v_precizie := '(' || v_rec_tab(v_nr_col).col_precision || ',' || v_rec_tab(v_nr_col).col_scale || ')';
        WHEN 12 THEN v_tip_coloana := 'DATE'; v_precizie := '';
        WHEN 96 THEN v_tip_coloana := 'CHAR'; v_precizie := '(' || v_rec_tab(v_nr_col).col_max_len || ')';
        WHEN 112 THEN v_tip_coloana := 'CLOB'; v_precizie := '';
        WHEN 113 THEN v_tip_coloana := 'BLOB'; v_precizie := '';
        WHEN 109 THEN v_tip_coloana := 'XMLTYPE'; v_precizie := '';
        WHEN 101 THEN v_tip_coloana := 'BINARY_DOUBLE'; v_precizie := '';
        WHEN 100 THEN v_tip_coloana := 'BINARY_FLOAT'; v_precizie := '';
        WHEN 8 THEN v_tip_coloana := 'LONG'; v_precizie := '';
        WHEN 180 THEN v_tip_coloana := 'TIMESTAMP'; v_precizie :='(' || v_rec_tab(v_nr_col).col_scale || ')';
        WHEN 181 THEN v_tip_coloana := 'TIMESTAMP' || '(' || v_rec_tab(v_nr_col).col_scale || ') ' || 'WITH TIME ZONE'; v_precizie := '';
        WHEN 231 THEN v_tip_coloana := 'TIMESTAMP' || '(' || v_rec_tab(v_nr_col).col_scale || ') ' || 'WITH LOCAL TIME ZONE'; v_precizie := '';
        WHEN 114 THEN v_tip_coloana := 'BFILE'; v_precizie := '';
        WHEN 23 THEN v_tip_coloana := 'RAW'; v_precizie := '(' || v_rec_tab(v_nr_col).col_max_len || ')';
        WHEN 11 THEN v_tip_coloana := 'ROWID'; v_precizie := '';
        WHEN 109 THEN v_tip_coloana := 'URITYPE'; v_precizie := '';
      END CASE; 
      RETURN v_tip_coloana||v_precizie;
END;


create or replace procedure exportTabel(p_nume_tabel IN varchar2) as
    v_id_cursor_descriere number;
    v_id_cursor_selectare number;
    v_ok number;
    
    v_tip_date varchar2(200);
    v_nume_coloana varchar2(200);
    
    v_sir varchar2(4000);
    v_valoare varchar2(4000);
    v_pos number;
    
    TYPE tipuri_date IS VARRAY(100) OF varchar2(20);
    v_lista tipuri_date;
    
    v_rec_tab dbms_sql.desc_tab;
    v_nr_col number;
    v_total_coloane number;
    
    v_fisier utl_file.file_type;
    
    v_coloane varchar2(1000):='(';
    
    v_nr integer;

begin
    v_fisier:=utl_file.fopen('MYDIR','exportTable.sql','W',4000);
    
    v_lista:=tipuri_date();
    
    --scrierea pentru a sterge tabelul
    utl_file.put_line(v_fisier,'DROP TABLE '||p_nume_tabel||' CASCADE CONSTRAINTS');
    utl_file.put_line(v_fisier,'/');
    
    --descrierea tabelului pentru a-l putea crea
    v_id_cursor_descriere:=dbms_sql.open_cursor;
    dbms_sql.parse(v_id_cursor_descriere,'select * from '||p_nume_tabel,dbms_sql.native);
    v_ok:=dbms_sql.execute(v_id_cursor_descriere);
    dbms_sql.describe_columns(v_id_cursor_descriere,v_total_coloane,v_rec_tab);
    
    
    --scriere in fisier pentru creare tabel
    v_nr:=0;
    
    v_nr_col:=v_rec_tab.first;
    v_sir:='CREATE TABLE '||p_nume_tabel||' ( ';
    if(v_nr_col is not null) then
        loop
            v_nume_coloana:=v_rec_tab(v_nr_col).col_name;
            
            --adauga nume coloana la lista
            if(v_nume_coloana!='CREAT_LA' and v_nume_coloana!='ACTUALIZAT_LA')then
                if(v_nr>0)then
                    v_coloane:=v_coloane||',';
                end if;
                v_coloane:=v_coloane||v_nume_coloana;
            end if;
            
            
            v_tip_date:=gettype(v_rec_tab,v_nr_col);
            
            v_lista.extend;
            v_lista(v_nr_col):=v_tip_date;
            
            if(v_nr_col!=1) then
                v_sir:=concat(v_sir,' , ');
            end if;
            v_sir:=concat(v_sir,v_nume_coloana||' '||v_tip_date);
        
            
            v_nr_col:=v_rec_tab.next(v_nr_col);
            v_nr:=v_nr+1;
            exit when (v_nr_col is null);
        end loop;
    end if;
    
    v_coloane:=v_coloane||')';
    
    v_sir:=concat(v_sir,' )');
    
    utl_file.put_line(v_fisier,v_sir);
    utl_file.put_line(v_fisier,'/');
    
    dbms_sql.close_cursor(v_id_cursor_descriere);
    
    --scriere inserari
    v_id_cursor_selectare:=dbms_sql.open_cursor;
    dbms_sql.parse(v_id_cursor_selectare,'select * from '||p_nume_tabel,dbms_sql.native);

    for v_nr in 1..v_total_coloane loop
        dbms_sql.define_column(v_id_cursor_selectare,v_nr,v_valoare,4000);
    end loop;
    v_ok:=dbms_sql.execute(v_id_cursor_selectare);
    
    loop
        if dbms_sql.fetch_rows(v_id_cursor_selectare)>0 then
        
            utl_file.put(v_fisier,'INSERT INTO '||p_nume_tabel||v_coloane||' VALUES ( ');
            
            for v_nr in 1..v_total_coloane loop
                
                dbms_sql.column_value(v_id_cursor_selectare,v_nr,v_valoare);
                
                v_pos:=instr(v_lista(v_nr),'VARCHAR2');
                if v_pos = 0 then
                    v_pos:=instr(v_lista(v_nr),'CHAR');
                end if;
                
                if v_pos=0 then
                    v_pos:=instr(v_lista(v_nr),'DATE');
                    if v_pos!=0 then
                        v_pos:=-1;
                    end if;
                end if;
                
                if(v_pos!=-1)then
                    if(v_nr!=1) then
                        utl_file.put(v_fisier,' , ');
                    end if;
                    
                    if(v_pos=0)then
                        utl_file.put(v_fisier,v_valoare);
                    else
                        utl_file.put(v_fisier,chr(39));
                        utl_file.put(v_fisier,v_valoare);
                        utl_file.put(v_fisier,chr(39));
                    end if;
                end if;          
            end loop;
            utl_file.put_line(v_fisier,' );');

        else
            exit;
        end if;
    end loop;
    utl_file.put_line(v_fisier,'COMMIT;');
    
    dbms_sql.close_cursor(v_id_cursor_selectare);
    
    utl_file.fclose(v_fisier);
end;

delete from animale;
describe animale;

declare
begin
    exportTabel('ANIMALE');
end;

select * from animale;

delete from animale;

INSERT INTO ANIMALE(ID,DENUMIRE_POPULARA,DENUMIRE_STINTIFICA,MINI_DESCRIERE,ETIMOLOGIE,ORIGINE,CLASA,HABITAT,INVAZIVA,STARE_DE_CONSERVARE,REGIM_ALIMENTAR,DIETA,MOD_DE_INMULTIRE,REPRODUCERE,DEZVOLTARE,VIATA,ISTORIE,DUSMANI_NATURALI,NR_ACCESARI,NR_SALVARI,NR_DESCARCARI) VALUES ( 1 , 'a' , 'a' , 'a' , '' , '' , '' , '' , '' , 'b' , '' , '' , '' , '' , '' , '' , '' , '' , 0 , 0 , 0 );
INSERT INTO ANIMALE(ID,DENUMIRE_POPULARA,DENUMIRE_STINTIFICA,MINI_DESCRIERE,ETIMOLOGIE,ORIGINE,CLASA,HABITAT,INVAZIVA,STARE_DE_CONSERVARE,REGIM_ALIMENTAR,DIETA,MOD_DE_INMULTIRE,REPRODUCERE,DEZVOLTARE,VIATA,ISTORIE,DUSMANI_NATURALI,NR_ACCESARI,NR_SALVARI,NR_DESCARCARI) VALUES ( 2 , 'c' , 'a' , 'a' , '' , '' , '' , '' , '' , 'b' , '' , '' , '' , '' , '' , '' , '' , '' , 0 , 0 , 0 );


select mini_descriere from animale where id=1;

select name,line,text from user_source where name='EXPORTTABEL' order by line asc;

-------------------------

    v_id_cursor_selectare:=dbms_sql.open_cursor;
    dbms_sql.parse(v_id_cursor_selectare,'select * from '||p_nume_tabel,dbms_sql.native);
    for v_nr in 1..v_total_coloane loop
        dbms_sql.define_column(v_id_cursor_selectare,v_nr,v_valoare,500);
    end loop;
    
    dbms_sql.execute(v_id_cursor_selectare);
    
    loop
        if(dbms_sql.fetch_rows(v_id_cursor_selectare)>0) then
            v_sir:='INSERT INTO '||p_nume_tabel||' VALUES ( ';
            for v_nr in 1..v_total_coloane loop
                if(v_nr!=1) then
                    v_sir:=concat(v_sir,' , ');
                end if;
                dbms_sql.column_value(v_id_cursor_selectare,v_nr,v_valoare);
                v_sir:=concat(v_sir,v_valoare);
            end loop;
            v_sir:=concat(v_sir,' );');
            
            utl_file.put_line(v_fisier,v_sir);
        else
            exit;
        end if;
    end loop;
    utl_file.put_line(v_fisier,'COMMIT;');
    
    dbms_sql.close_cursor(v_id_cursor_selectare);
----------
select * from user_source where name='EXPORTTABEL' order by line;