# Kacmałpka - kilka promili szczęścia

***

# Setup środowiska

## Docker
Zainstaluj Dockera na swój system operacyjny zgodnie z instrukcją, wymaga również rejestracji (darmowa na użytek edukacyjny)
* [Windows](https://docs.docker.com/desktop/install/windows-install/)  
    _Wymaga włączonej wirtualizacji [WSL2](https://learn.microsoft.com/en-us/windows/wsl/install)_  
* [Ubuntu](https://docs.docker.com/engine/install/ubuntu/)  
    _Instalacja trywialna 8\)_


## Git
Na windowsie zainstaluj [gita](https://git-scm.com/download/win), na linuxie większość distro ma już gita wbudowanego, a jak nie to 	``` sudo apt-get install git``` 

Nagrałem [tutorial](https://drive.google.com/file/d/1twfRnFZmbQT2CtzuTbE__B-suiLWTMF8/view?usp=drive_link) jak założyć gita i dodać go do VSCode

Jest też osobny tutorial o robieniu nowych gałęzi: [w bashu](https://drive.google.com/file/d/1C40tE7HFhGVPaRyfyJunMWmXyAKJ2qJP/view?usp=drive_link) i [w VSCode](https://drive.google.com/file/d/1F2xw1u_UAoMan5g-fif-UpohnORq_DaX/view?usp=drive_link)


Cheat sheat przydatnych komend:
```
git clone git@gitlab.com:pzsi/kacmalpka.git		//klonowanie maina
git clone --branch <nazwa_brancha> git@gitlab.com:pzsi/kacmalpka.git <nazwa_folderu>	//klonowanie gałęzi
git checkout -b <nazwa_brancha>		//utowrzenie brancha (odpalamy z maina albo istniejącej gałęzi)
git add -A			//dodanie wszystkich zmian
git commit			//zapisanie commita
git push			//wysłanie commita na serwer
git diff			//wyświetlenie różnic przed addem
git status			//wyświetlenie różnic po addzie
git reset --hard <commit> 	//powrót do poprzedniego commita na gałęzi
```

## Uruchomienie dockera
[Jak pierwszy raz uruchomić naszą aplikację w Dockerze](https://drive.google.com/file/d/1jGPhorSERW9MPZjUTeDRufIyqmM2duqX/view?usp=drive_link)

Przydatne komendy do dokera:
```
docker-compose up --build		//zbudowanie
docker-compose up			//odpalenie
docker-compose down -v			//usunięcie !UWAGA! - usunie też wszystkie dane z bazy
docker inspect <nazwa>			//info o kontenerze albo dysku
```

Przydatne komendy do kacmalpka-app:
```
docker exec -t -i kacmalpka-app bash	//wejscie do kacmalpka-app (musi być odpalone w dockerze)
python3 manage.py makemigrations store	//utworzenie nowych migracji po zmianach w models.py
python3 manage.py migrate		//wykonanie migracji do BD
python3 manage.py createsuperuser	//utworzenie superusera do panelu administracyjnego
```

Przydatne komendy do kacmalpka-mysql:
```
docker exec -t -i kacmalpka-mysql bash			//wejscie do kacmalpka-mysql
mysql --user=kacmalpka --password=kacmalpka kacmalpka	//logowanie do bazy
mysqldump -uroot-p kacmalpka > backup.sql		//utworzenie backupu danych
mysql -uroot -p kacmalpka < backup.sql			//przywrócenie danych z backupu
docker cp kacmalpka-mysql:/backup.sql C:\Users\....	//skopiowanie backupu z dockera do hosta
docker cp C:\Users\...\backup.sql kacmalpka-mysql:/	//skopiowanie backupu z hosta do dockera
```

username do bazy:	kacmalpka  
hasło:				kacmalpka  
hasło roota:		rootkacmalpka

(trzeba będzie w przyszłości zmienić dla bezpieczeństwa jak by ewentualnie wisiało na internecie)

***

# Działanie Apki

Po odpaleniu apki w dockerze jest komunikacja:
- [ ] z wersją html-ową na [localhost:8000](http://localhost:8000)
- [ ] panel administracyjny na [localhost:8000/admin](http://localhost:8000/admin)
- [ ] z wersją reactową na [localhost:3000](http://localhost:3000)
- [ ] z bazą danych na porcie localhost:3306 - można połączyć się np. programem [dbeaver](https://dbeaver.io/)
