--legat de proceduri/functii administrator

select NUME_UTILIZATOR , PAROLA , EMAIL , TELEFON , ADMINISTRATOR 
from utilizatori 
order by punctaj_cuvant_camp('remus',NUME_UTILIZATOR,100) desc;