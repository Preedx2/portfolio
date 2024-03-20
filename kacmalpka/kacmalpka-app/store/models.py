from django.db import models


class Roles(models.Model):
    role_id = models.IntegerField(primary_key=True)
    role_name = models.CharField(max_length=32)


class Users(models.Model):
    usr_id = models.IntegerField(primary_key=True)
    usr_name = models.CharField(max_length=64)
    usr_password = models.CharField(max_length=64)
    usr_date = models.DateTimeField()
    usr_email = models.CharField(max_length=255)
    role_id = models.ForeignKey(Roles, on_delete=models.CASCADE)


class Addresses(models.Model):
    adrs_id = models.IntegerField(primary_key=True)
    usr_id = models.OneToOneField(Users, on_delete=models.CASCADE)
    adrs_line1 = models.CharField(max_length=255)
    adrs_line2 = models.CharField(max_length=1023)
    adrs_zipcode = models.CharField(max_length=127)
    adrs_city = models.CharField(max_length=127)


class Transactions(models.Model):
    trans_id = models.IntegerField(primary_key=True)
    usr_id = models.ForeignKey(Users, on_delete=models.CASCADE)
    adrs_id = models.ForeignKey(Addresses, on_delete=models.CASCADE)
    trans_buy_date = models.DateTimeField()
    trans_sent_date = models.DateTimeField(null="True")
    trans_recieved = models.BooleanField()


class Products(models.Model):
    prod_id = models.IntegerField(primary_key=True)
    prod_name_short = models.CharField(max_length=255)
    prod_name_long = models.CharField(max_length=1023, null="True")
    prod_description = models.CharField(max_length=10000)   # trzeba zmniejszyc bo sie krzaczy
    prod_image = models.CharField(max_length=255)


class Tags(models.Model):
    tag_id = models.IntegerField(primary_key=True)
    tag_descr = models.CharField(max_length=32)


class ProductTags(models.Model):
    prod_id = models.ForeignKey(Products, on_delete=models.CASCADE)
    tag_id = models.ForeignKey(Tags, on_delete=models.CASCADE)


class Prices(models.Model):
    price_id = models.IntegerField(primary_key=True)
    price_price = models.IntegerField()
    price_date = models.DateTimeField()
    prod_id = models.OneToOneField(Products, on_delete=models.CASCADE)


class Reviews(models.Model):
    rev_id = models.IntegerField(primary_key=True)
    rev_score = models.IntegerField()
    rev_text = models.CharField(max_length=15000, null="True")
    rev_date = models.DateTimeField()
    prod_id = models.ForeignKey(Products, on_delete=models.CASCADE)


class TransactionProducts(models.Model):
    prod_id = models.ForeignKey(Products, on_delete=models.CASCADE)
    trans_id = models.ForeignKey(Transactions, on_delete=models.CASCADE)
    trans_prod_amount = models.IntegerField()
