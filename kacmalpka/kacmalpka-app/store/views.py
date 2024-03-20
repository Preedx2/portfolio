from django.shortcuts import render
from django.http import HttpResponse
from django.conf import settings
from django.template import loader
from .models import Products, ProductTags, Tags
from rest_framework.response import Response
from rest_framework.decorators import api_view
from rest_framework import status
from .serializers import *

def index(request):
    template = loader.get_template("store/index.html")
    context = {
        "test": 0
    }
    return HttpResponse(template.render(context, request))


def likiery(request):
    template = loader.get_template("store/likiery.html")
    context = {
        "test": 0
    }
    return HttpResponse(template.render(context, request))


def whisky(request):
    template = loader.get_template("store/whisky.html")
    context = {
        "test": 0
    }
    return HttpResponse(template.render(context, request))


def wodka(request):
    template = loader.get_template("store/wodka.html")
    context = {
        "test": 0
    }
    return HttpResponse(template.render(context, request))


def wodka(request):
    products = Products.objects.all()
    context = {
        'products': products,
    }
    return render(request, 'store/wodka.html', context)


def login(request):
    template = loader.get_template("store/log.html")
    context = {
        "test": 0
    }
    return HttpResponse(template.render(context, request))


def register(request):
    template = loader.get_template("store/reg.html")
    context = {
        "test": 0
    }
    return HttpResponse(template.render(context, request))

# DJango API
@api_view(['GET', 'POST'])
def products_list(request):
    if request.method == 'GET':
        data = Products.objects.all()
        serializer = ProductsSerializer(data, context={'request': request}, many=True)
        return Response(serializer.data)
    elif request.method == 'POST':
        serializer = ProductsSerializer(data=request.data)
        if serializer.is_valid():
            serializer.save()
            return Response(status=status.HTTP_201_CREATED)

        return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)


@api_view(['PUT', 'DELETE'])
def products_detail(request, prod_id):
    try:
        product = Products.objects.get(pk=prod_id)
    except Products.DoesNotExist:
        return Response(status=status.HTTP_404_NOT_FOUND)

    if request.method == 'PUT':
        serializer = ProductsSerializer(product, data=request.data,
                                        context={'request': request})
        if serializer.is_valid():
            serializer.save()
            return Response(status=status.HTTP_204_NO_CONTENT)
        return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)
    elif request.method == 'DELETE':
        product.delete()
        return Response(status=status.HTTP_204_NO_CONTENT)


@api_view(['GET', 'POST'])
def prodtags_list(request):
    if request.method == 'GET':
        data = ProductTags.objects.all()
        serializer = ProductTagsSerializer(data, context={'request': request}, many=True)
        return Response(serializer.data)
    elif request.method == 'POST':
        serializer = ProductsSerializer(data=request.data)
        if serializer.is_valid():
            serializer.save()
            return Response(status=status.HTTP_201_CREATED)

        return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)

@api_view(['GET', 'POST'])
def tags_list(request):
    if request.method == 'GET':
        data = Tags.objects.all()
        serializer = TagsSerializer(data, context={'request': request}, many=True)
        return Response(serializer.data)
    elif request.method == 'POST':
        serializer = TagsSerializer(data=request.data)
        if serializer.is_valid():
            serializer.save()
            return Response(status=status.HTTP_201_CREATED)

        return Response(serializer.errors, status=status.HTTP_400_BAD_REQUEST)