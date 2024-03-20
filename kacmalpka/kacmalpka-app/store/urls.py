from django.urls import path, re_path
from .views import wodka

from . import views

urlpatterns = [
    path("", views.index, name="index"),
    path("index.html", views.index, name="index"),
    path("log.html", views.login, name="index"),
    path("reg.html", views.register, name="index"),
    path("likiery.html", views.likiery, name="index"),
    path("whisky.html", views.whisky, name="index"),
    path("wodka.html", views.wodka, name="index"),
    path('wodka/', wodka, name='wodka'),
    re_path(r'^api/products/$', views.products_list),
    re_path(r'^api/prod_tags/$', views.prodtags_list),
    re_path(r'^api/tags/$', views.tags_list),
    re_path(r'^api/products/([0-9])$', views.products_detail),
]
