Object Oriented Database
====

Bored of all the semi object oriented database handlers in php,  
and inspired by the fully object oriented working of mongoDB in nodejs, I made OODB.  
It's not a real database, just a class to access them easily and in a readable way.  

Creating the connection and the table
====

You just import the OODB main class.  
This will help you to import the database of the type you want to use. 
```php
include('OODB/OODB.php');
```  
***
Now you are ready to instantiate the database handler.  
In this example I'm connecting to a Mysql database. As of PHP 5.3 you can use
```php
$database = OODB::mysql($host, $database, $user, $password);
```
But people with a lower PHP version should use
```php
$database = OODB::get("mysql", $host, $database, $user, $password);
```
Note that creating the handler differs from creating a mysqli handler, which is
```php
$database = new mysqli($host, $user, $password, $database); # just the order of arguments
```
***

After you created the handler, you can select a table in the database.
```php
$table = $database->tablename;
```
Yes, it actually is that easy!
But this is not even te most awesome part.

Getting, setting, deleting
====

This, is where the real power starts.
Let's say we aready connected to the database, and chose a table as we did in the previous section
and we have already set up an table with the following structure (You can't create tables with this wrapper yet)

<table>
  <tr>
    <td>id (auto incement)</td>
    <td>firstname</td>
    <td>Lastname</td>
    <td>Age</td>
  </tr>
</table>

But, the table is empty!
Let's add some info!

```php
$table->insert(array(
  'firstname' => 'Markus',
  'lastname' => 'Persson',
  'Age' => 42
));
```

That's it! Now this info is inserted. There is no restriction in what order you add the arguments.
Just use the name. The script automaticly sees 'id' is auto_increment, so it won't give you an error.
The script also prevents any form of mysql injections, by using bind_param automaticly.
When you try to insert a non existing column, it will give you an instructive error.

Now the table looks like this:

<table>
  <tr>
    <th>id (auto incement)</th>
    <th>firstname</th>
    <th>Lastname</th>
    <th>Age</th>
  </tr>
  <tr>
    <td>0</td>
    <td>Markus</td>
    <td>Person</td>
    <td>42</td>
  </tr>
</table>

I now magically added some more:

<table>
  <tr>
    <th>id (auto incement)</th>
    <th>firstname</th>
    <th>Lastname</th>
    <th>Age</th>
  </tr>
  <tr>
    <td>0</td>
    <td>Markus</td>
    <td>Person</td>
    <td>42</td>
  </tr>
  <tr>
    <td>1</td>
    <td>Barrack</td>
    <td>Obama</td>
    <td>51</td>
  </tr>
  <tr>
    <td>2</td>
    <td>Michiel</td>
    <td>Dral</td>
    <td>16</td>
  </tr>
</table>

Right, but what if I want to find the age of Markus Persson back!
Let's see what we can do

```php
$result = $table->find(array(
  'firstname' => 'Markus',
  'lastname' => 'Persson',
));

if(count($result) !=== 1) {
  die("None, or more than one found? Strange..");
}

$markus = $result[0];
echo "The age of ".$markus['firstname']." ".$markus['lastname']." is ".$markus['age'];
```

Easy as that!
I know the $markus['firstname'] and lastname are a bit expendable, but I just want to show how all data get's into a clean, easy to use array.

Imagine you want to remove everybody older than 18 years old.
That would be done like this:

```php
/* Note how I use $database here again, it's for easier comparisons */
$table->delete(array(
  'age' => $database::gte(18)
));
```

Or without the $database variable, it works like mongodb


```php
$table->delete(array(
  'age' => array('$gte' => 18)
));
```

Documentation
===
Update:
You can use update using
```php
$table->update($where, $new);
```
So in the previous example, if I would like to set the age of everybody with the firstname "Markus" to 1337, I would do
You can also update using
```php
$table->update(array(
  'firstname' => 'Markus'
), array(
  'age' => 1337
));
```

I'm writing a [api reference](https://github.com/dralletje/OODB/wiki/Api-reference) soon :)
