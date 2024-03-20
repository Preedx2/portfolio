from rest_framework import serializers
from .models import Roles, Users, Addresses, Transactions, Products, Tags, Prices,\
    Reviews, ProductTags, TransactionProducts


class RolesSerializer(serializers.ModelSerializer):
    class Meta:
        model = Roles
        fields = ['role_id', 'role_name']


class PricesSerializer(serializers.ModelSerializer):
    class Meta:
        model = Prices
        # fields = ['price_id', 'price_price', 'price_date', 'prod_id']
        fields = ['price_id', 'price_price', 'price_date']


class TagsSerializer(serializers.ModelSerializer):
    class Meta:
        model = Tags
        fields = ['tag_id', 'tag_descr']


class ProductTagsSerializer(serializers.ModelSerializer):
    tag_id = TagsSerializer(read_only=True)

    class Meta:
        model = ProductTags
        fields = ['tag_id']


class ProductsSerializer(serializers.ModelSerializer):
    prices = PricesSerializer()
    producttags_set = ProductTagsSerializer(many=True, read_only=True)

    class Meta:
        model = Products
        # fields = ['prod_id', 'prod_name_short', 'prod_name_long', 'prod_description', 'prod_image', 'prices']
        fields = ['prod_id', 'prod_name_short', 'prod_name_long', 'prod_description', 'prod_image',
                  'prices', 'producttags_set']
        # fields = '__all__'
