## About Challange
<p>Write a program that can price a cart of products, accept multiple products, combine offers, and display a total detailed bill in different currencies (based on user selection).</p>

## Structure And Design Pattern 
<ul>
	<li>Laravel Farmework used on this application </li>
	<li><strong>HMVC</strong>  Architecture Pattern</li>
	<li><strong>Repository</strong> Pattern</li>
	<li><strong>Strategy</strong> Pattern for discounts </li> 
</ul>

<p>
	<strong> this our source web site <a href="https://usd.mconvert.net"> https://usd.mconvert.net</a>< i scraping  the converted values and it details like name symbol value and then calculate the ration on this date ot get order price as requested on this date</strong>
</p>

## Installation

<p> clone project 
	<ul>
		<li>create database </li>
		<li>duplicat .env.example create your .env file</li>
		<li> put your configuration like db name password</li>
		<li> <strong> run composer install </strong> </li>
		<li> <strong> php artisan key:generate </strong> </li>
		<li> <strong> php artisan module:migrate </strong> </li>
		<li> <strong> php artisan module:seed </strong> </li>
		<li> <strong> ./vendor/bin/phpunit </strong> if test should pass correctly</li>
		<li> <strong> php artisan serve </strong> </li>
	</ul>

<p> send post request to the follwong url<strong> like  <a>http://127.0.0.1:8000/api/orders/store</a> </strong>  </p>

## Request
<code>
	{
	    "cart": [
	        "T-shirt",
	        "T-shirt",
	        "Shoes",
	        "Jacket"
	    ],
	    "currency": "EGP"
	}	
</code>

## Response
<code>
	{
    "data": {
        "Subtotal": "£1050.6024",
        "Taxes": "£147.084336",
        "discount": [
            {
                "name": "Jacket",
                "discount_off": "£156.82155",
                "discount_amount": "50%"
            },
            {
                "name": "Shoes",
                "discount_off": "£39.20931",
                "discount_amount": "10%"
            }
        ],
        "total": "£1001.655876"
    }
}
</code>

## Warning

<p> <strong>shoes</strong> and <strong>Shoes</strong> Laravel is insensitive not case sensitive  on <strong>exists</strong> so i handed it from code and it this product will be ignored </p>


## License

The Laravel framework 