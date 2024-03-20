from django.contrib import admin
from .models import Roles, Users, Addresses, Transactions, Products, Tags, \
    Prices, Reviews, ProductTags, TransactionProducts


class RolesAdmin(admin.ModelAdmin):
    list_display = ('role_id', 'role_name')


class UsersAdmin(admin.ModelAdmin):
    list_display = ('usr_id', 'usr_name', 'usr_password', 'usr_date',
                    'usr_email', 'role_id')


class AddressesAdmin(admin.ModelAdmin):
    list_display = ('adrs_id', 'usr_id', 'adrs_line1', 'adrs_line2',
                    'adrs_zipcode', 'adrs_city')


# TODO mielismy zmienic ten model
class TransactionsAdmin(admin.ModelAdmin):
    list_display = ('trans_id', 'usr_id', 'adrs_id', 'trans_buy_date',
                    'trans_sent_date', 'trans_recieved')


class ProductsAdmin(admin.ModelAdmin):
    list_display = ('prod_id', 'prod_name_short', 'prod_name_long',
                    'prod_description', 'prod_image')


class TagsAdmin(admin.ModelAdmin):
    list_display = ('tag_id', 'tag_descr')


class PricesAdmin(admin.ModelAdmin):
    list_display = ('price_id', 'price_price', 'price_date', 'prod_id')


class ReviewsAdmin(admin.ModelAdmin):
    list_display = ('rev_id', 'rev_score', 'rev_text', 'rev_date', 'prod_id')


class ProductTagsAdmin(admin.ModelAdmin):
    list_display = ('prod_id', 'tag_id')


class TransactionProductsAdmin(admin.ModelAdmin):
    list_display = ('prod_id', 'trans_id', 'trans_prod_amount')


# Register your models here.
admin.site.register(Roles, RolesAdmin)
admin.site.register(Users, UsersAdmin)
admin.site.register(Addresses, AddressesAdmin)
admin.site.register(Transactions, TransactionsAdmin)
admin.site.register(Products, ProductsAdmin)
admin.site.register(Tags, TagsAdmin)
admin.site.register(Prices, PricesAdmin)
admin.site.register(Reviews, ReviewsAdmin)
admin.site.register(ProductTags, ProductTagsAdmin)
admin.site.register(TransactionProducts, TransactionProductsAdmin)
