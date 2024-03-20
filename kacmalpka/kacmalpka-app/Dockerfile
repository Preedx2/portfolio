FROM python:3.9-slim-buster

WORKDIR /app

COPY . /app

RUN pip install --trusted-host pypi.python.org -r requirements.txt

EXPOSE 8000

# Tutaj mozemy dodac zmienne srodowiskowe
ENV SOME_ENV 1

CMD ["python", "manage.py", "runserver", "0.0.0.0:8000"]
