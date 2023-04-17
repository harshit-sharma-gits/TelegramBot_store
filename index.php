<?php

use SergiX44\Nutgram\Conversations\Conversation;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardButton;
use SergiX44\Nutgram\Telegram\Types\Keyboard\InlineKeyboardMarkup;
use SergiX44\Nutgram\Telegram\Types\Common\Update;
use SergiX44\Nutgram\Telegram\Types\Internal\InputFile;
use SergiX44\Nutgram\Nutgram;

require './vendor/autoload.php';


/*
** Bot configuration. This $botToken must not be copied, it is for this
** specific project only.
*/
$botToken = '6227854714:AAGIOFybOQSqD1bx6diwd0qzwPmSz1LZ14k';
$config = [
    'timeout' => 10,
];


/*
** Defining a new class with user extensions extending the Nutgram Bot Class
*/
class User extends Nutgram {
    public $name = "NA";
    public $deliveryAddress = "NA";
    public $totalPrice;
    public $quantity;

    public $household = array(0, 0, 0, 0, 0);
    /*
    ** 1. Harpic
    ** 2. Vim Bar
    ** 3. Rin Bar
    ** 4. Bathing Soap
    ** 5. Surf Excel
    */
    public $medicine = array(0, 0, 0, 0, 0);
    /*
    ** Now the Medicine menu, choose from:
    ** 1. Combiflam
    ** 2. Paracetamol
    ** 3. Betadine
    ** 4. Whisper
    ** 5. Vicks
    */
    public $food = array(0, 0, 0, 0, 0);
    /*
    ** 1. Natkhat
    ** 2. Lays
    ** 3. Maggie
    ** 4. Oats
    ** 5. Pepsi
    */

    public function setName($name) {
        $this->name = $name;
    }
    public function getName() {
        return $this->name;
    }
    public function setDeliveryAddress($deliveryAddress) {
        $this->deliveryAddress = $deliveryAddress;
    }
    public function getDeliveryAddress() {
        return $this->deliveryAddress;
    }
    public function setTotalPrice($totalPrice) {
        $this->totalPrice = $totalPrice;
    }
    public function getTotalPrice() {
        return $this->totalPrice;
    }
    public function setQuantity($quantity) {
        $this->quantity = $quantity;
    }
    public function getQuantity() {
        return $this->quantity;
    }
}

$bot = new User($botToken, $config);


/*
** Commands of the bot starts from this section
**
** firstStep - Enter Name
** secondStep - Choose items from menu
** thirdStep - Enter Delivery Address
** fourthStep - send QR
*/
$bot->onCommand('start', 'firstStep');

function firstStep(User $bot) {

    $bot->setName("NA");
    $bot->setDeliveryAddress("NA");
    $bot->setTotalPrice(0);
    $bot->setQuantity(0);

$msg = "
How to use the bot?
Here are the few instructions that will help you to use the bot:

1- Send your name to the bot in the form of 'My name is your name'.

2- Select the categories of items you want to purchase. As of now we have three categories - Household items, Medicine and Food items.

3- Select the items you want to purchase and give the quantity. How many of that particular product you want?

4- The bot will display the total price of items you want from that category. If you want to purchase more items from some other category again give /categories command.

5- At last when you are done shopping from store reply 'DONE'.

6- Then choose the type of delivery you want 'Walk in' or 'Deliver at home'.

7- On choosing Deliver at home bot will send you a QR code. Do the payment and shopkeeper will deliver at your home.

8- Thanks for shopping with us. Hope you enjoyed our service.
";

    $bot->sendMessage("$msg");
}


/*
**  We have to store the name and delivery address as well.
*/

$bot->onText('My name is {name}', function (User $bot, string $name) {
    $bot->sendMessage("Hi $name");
    $bot->sendMessage("Let me store your name in my database.");

    $bot->setName($name);
    $naam = $bot->getName();
    $bot->sendMessage("$naam is stored in my database. You can now proceed with your shopping!");
});


$bot->onText('Delivery address is {address}', function (User $bot, string $address) {
    $bot->sendMessage("Let me store your address in my database.");

    $bot->setDeliveryAddress($address);
    $naam = $bot->getDeliveryAddress();
    $bot->sendMessage("$naam \nis stored in my database. You can now proceed with your shopping!");
});


/*
**  Main Category choosing menu
**  Choose between:
**  1. Food Items
**  2. Household Items
**  3. Medicines
**  4. Electronics Items
*/

$bot->onCommand('categories', 'mainCatMenu');

function mainCatMenu(User $bot)
{
    $bot->sendMessage('Choose Category:', [
        'reply_markup' => InlineKeyboardMarkup::make()
            ->addRow(
                InlineKeyboardButton::make(
                    'Food items',
                callback_data: 'type:a'
                ),
            )
            ->addRow(
                InlineKeyboardButton::make(
                    'Household items',
                callback_data: 'type:b'
                ),
            )
            ->addRow(
                InlineKeyboardButton::make(
                    'Medicines',
                callback_data: 'type:c'
                ),
            )
    ]);
}

$bot->onCallbackQueryData('type:a', function (User $bot) {
    $bot->answerCallbackQuery([
        'text' => 'You selected Food items'
    ]);
    foodMenu($bot);
});

$bot->onCallbackQueryData('type:b', function (User $bot) {
    $bot->answerCallbackQuery([
        'text' => 'You selected Household items'
    ]);
    householdMenu($bot);
});

$bot->onCallbackQueryData('type:c', function (User $bot) {
    $bot->answerCallbackQuery([
        'text' => 'You selected Medicines'
    ]);
    medicineMenu($bot);
});


/*
** Main Category Menu Ends
** 
** Now the food menu, choose from:
** 1. Natkhat
** 2. Lays
** 3. Maggie
** 4. Oats
** 5. Pepsi
*/

function foodMenu(User $bot)
{
    $bot->sendMessage('Choose Food Item from this list:', [
        'reply_markup' => InlineKeyboardMarkup::make()
            ->addRow(
                InlineKeyboardButton::make(
                    'Natkhat',
                callback_data: 'food:a'
                ),
            )
            ->addRow(
                InlineKeyboardButton::make(
                    'Lays',
                callback_data: 'food:b'
                ),
            )
            ->addRow(
                InlineKeyboardButton::make(
                    'Maggie',
                callback_data: 'food:c'
                ),
            )
            ->addRow(
                InlineKeyboardButton::make(
                    'Oats',
                callback_data: 'food:d'
                ),
            )
            ->addRow(
                InlineKeyboardButton::make(
                    'Pepsi',
                callback_data: 'food:e'
                ),
            )
            ->addRow(
                InlineKeyboardButton::make(
                    'Check Out',
                callback_data: 'check:out'
                ),
            )
    ]);
}

$bot->onCallbackQueryData('food:a', function (User $bot) {
    $bot->food[0]++;
    $n = $bot->food[0];
    $bot->answerCallbackQuery([
        'text' => "You selected $n Natkhat"
    ]);
});

$bot->onCallbackQueryData('food:b', function (User $bot) {
    $bot->food[1]++;
    $n = $bot->food[1];
    $bot->answerCallbackQuery([
        'text' => "You selected $n Lays"
    ]);
});

$bot->onCallbackQueryData('food:c', function (User $bot) {
    $bot->food[2]++;
    $n = $bot->food[2];
    $bot->answerCallbackQuery([
        'text' => "You selected $n Maggie"
    ]);
});

$bot->onCallbackQueryData('food:d', function (User $bot) {
    $bot->food[3]++;
    $n = $bot->food[3];
    $bot->answerCallbackQuery([
        'text' => "You selected $n Oats"
    ]);
});

$bot->onCallbackQueryData('food:e', function (User $bot) {
    $bot->food[4]++;
    $n = $bot->food[4];
    $bot->answerCallbackQuery([
        'text' => "You selected $n Pepsi"
    ]);
});


/*
** Food Menu Ends
** 
** Now the Houshold Items menu, choose from:
** 1. Harpic
** 2. Vim Bar
** 3. Rin Bar
** 4. Bathing Soap
** 5. Surf Excel
*/

function householdMenu(User $bot)
{
    $bot->sendMessage('Choose Household Item from this list:', [
        'reply_markup' => InlineKeyboardMarkup::make()
            ->addRow(
                InlineKeyboardButton::make(
                    'Harpic',
                callback_data: 'house:a'
                ),
            )
            ->addRow(
                InlineKeyboardButton::make(
                    'Vim Bar',
                callback_data: 'house:b'
                ),
            )
            ->addRow(
                InlineKeyboardButton::make(
                    'Rin Bar',
                callback_data: 'house:c'
                ),
            )
            ->addRow(
                InlineKeyboardButton::make(
                    'Bathing Soap',
                callback_data: 'house:d'
                ),
            )
            ->addRow(
                InlineKeyboardButton::make(
                    'Surf Excel',
                callback_data: 'house:e'
                ),
            )
            ->addRow(
                InlineKeyboardButton::make(
                    'Check Out',
                callback_data: 'check:out'
                ),
            )
    ]);
}

$bot->onCallbackQueryData('house:a', function (User $bot) {
    $bot->household[0]++;
    $n = $bot->household[0];
    $bot->answerCallbackQuery([
        'text' => "You selected $n Harpic"
    ]);
});

$bot->onCallbackQueryData('house:b', function (User $bot) {
    $bot->household[1]++;
    $n = $bot->household[1];
    $bot->answerCallbackQuery([
        'text' => "You selected $n Vim Bar"
    ]);
});

$bot->onCallbackQueryData('house:c', function (User $bot) {
    $bot->household[2]++;
    $n = $bot->household[2];
    $bot->answerCallbackQuery([
        'text' => "You selected $n Rin Bar"
    ]);
});

$bot->onCallbackQueryData('house:d', function (User $bot) {
    $bot->household[3]++;
    $n = $bot->household[3];
    $bot->answerCallbackQuery([
        'text' => "You selected $n Bathing Soap"
    ]);
});

$bot->onCallbackQueryData('house:e', function (User $bot) {
    $bot->household[4]++;
    $n = $bot->household[4];
    $bot->answerCallbackQuery([
        'text' => "You selected $n Surf Excel"
    ]);
});


/*
** Household Menu Ends
** 
** Now the Medicine menu, choose from:
** 1. Combiflam
** 2. Paracetamol
** 3. Betadine
** 4. Whisper
** 5. Vicks
*/

function medicineMenu(User $bot)
{
    $bot->sendMessage('Choose Medicine from this list:', [
        'reply_markup' => InlineKeyboardMarkup::make()
            ->addRow(
                InlineKeyboardButton::make(
                    'Combiflam',
                callback_data: 'medi:a'
                ),
            )
            ->addRow(
                InlineKeyboardButton::make(
                    'Paracetamol',
                callback_data: 'medi:b'
                ),
            )
            ->addRow(
                InlineKeyboardButton::make(
                    'Betadine',
                callback_data: 'medi:c'
                ),
            )
            ->addRow(
                InlineKeyboardButton::make(
                    'Whisper',
                callback_data: 'medi:d'
                ),
            )
            ->addRow(
                InlineKeyboardButton::make(
                    'Vicks',
                callback_data: 'medi:e'
                ),
            )
            ->addRow(
                InlineKeyboardButton::make(
                    'Check Out',
                callback_data: 'check:out'
                ),
            )
    ]);
}

$bot->onCallbackQueryData('medi:a', function (User $bot) {
    $bot->medicine[0]++;
    $n = $bot->medicine[0];
    $bot->answerCallbackQuery([
        'text' => "You selected $n Combiflam"
    ]);
});

$bot->onCallbackQueryData('medi:b', function (User $bot) {
    $bot->medicine[1]++;
    $n = $bot->medicine[1];
    $bot->answerCallbackQuery([
        'text' => "You selected $n Paracetamol"
    ]);
});

$bot->onCallbackQueryData('medi:c', function (User $bot) {
    $bot->medicine[2]++;
    $n = $bot->medicine[2];
    $bot->answerCallbackQuery([
        'text' => "You selected $n Betadine"
    ]);
});

$bot->onCallbackQueryData('medi:d', function (User $bot) {
    $bot->medicine[3]++;
    $n = $bot->medicine[3];
    $bot->answerCallbackQuery([
        'text' => "You selected $n Whisper"
    ]);
});

$bot->onCallbackQueryData('medi:e', function (User $bot) {
    $bot->medicine[4]++;
    $n = $bot->medicine[4];
    $bot->answerCallbackQuery([
        'text' => "You selected $n Vicks"
    ]);
});


/*
**  Every menu is decided now.
**  Now we have to have a function to handle the user checkout.
*/

$bot->onCallbackQueryData('check:out', function (User $bot) {



    if ($bot->name == "NA" && $bot->deliveryAddress == "NA")
    {
        $erMsg = "Please enter your Name and Delivery Address in the given format before checking out!";
        $erMsg .= "\n\nYou can refer to /start for the format.";
        $bot->sendMessage($erMsg);
        return;
    }
    if ($bot->name == "NA")
    {
        $erMsg = "Please enter your Name in the given format before checking out!";
        $erMsg .= "\n\nYou can refer to /start for the format.";
        $bot->sendMessage($erMsg);
        return;
    }
    if ($bot->deliveryAddress == "NA")
    {
        $erMsg = "Please enter your Delivery Address in the given format before checking out!";
        $erMsg .= "\n\nYou can refer to /start for the format.";
        $bot->sendMessage($erMsg);
        return;
    }



    $bot->sendMessage('Here is the list of items you have selected: ➡️');

    $foodSum = array_sum($bot->food);
    $houseSum = array_sum($bot->household);
    $medicineSum = array_sum($bot->medicine);

$foodMsg = "From the Food Items:\n\n";
$houseMsg = "From the Household Items:\n\n";
$medicineMsg = "From the Medicine Items:\n\n";
$bot->quantity=0;

    if ($foodSum == 0)
    {
        $foodMsg .= 'You have not selected anything';
    }
    else
    {
        if ($bot->food[0] != 0)
        {
            $n = $bot->food[0];
            $foodMsg .= "Natkhat: $n\n";
            $bot->quantity += $n;
        }
        if ($bot->food[1] != 0)
        {
            $n = $bot->food[1];
            $foodMsg .= "Lays: $n\n";
            $bot->quantity += $n;
        }
        if ($bot->food[2] != 0)
        {
            $n = $bot->food[2];
            $foodMsg .= "Maggie: $n\n";
            $bot->quantity += $n;
        }
        if ($bot->food[3] != 0)
        {
            $n = $bot->food[3];
            $foodMsg .= "Oats: $n\n";
            $bot->quantity += $n;
        }
        if ($bot->food[4] != 0)
        {
            $n = $bot->food[4];
            $foodMsg .= "Pepsi: $n\n";
            $bot->quantity += $n;
        }
    }

    if ($houseSum == 0)
    {
        $houseMsg .= 'You have not selected anything';
    }
    else
    {
        if ($bot->household[0] != 0)
        {
            $n = $bot->household[0];
            $houseMsg .= "Harpic: $n\n";
            $bot->quantity += $n;
        }
        if ($bot->household[1] != 0)
        {
            $n = $bot->household[1];
            $houseMsg .= "Vim Bar: $n\n";
            $bot->quantity += $n;
        }
        if ($bot->household[2] != 0)
        {
            $n = $bot->household[2];
            $houseMsg .= "Rin Bar: $n\n";
            $bot->quantity += $n;
        }
        if ($bot->household[3] != 0)
        {
            $n = $bot->household[3];
            $houseMsg .= "Bathing Soap: $n\n";
            $bot->quantity += $n;
        }
        if ($bot->household[4] != 0)
        {
            $n = $bot->household[4];
            $houseMsg .= "Surf Excel: $n\n";
            $bot->quantity += $n;
        }
    }

    if ($medicineSum == 0)
    {
        $medicineMsg .= 'You have not selected anything';
    }
    else
    {
        if ($bot->medicine[0] != 0)
        {
            $n = $bot->medicine[0];
            $medicineMsg .= "Combiflam: $n\n";
            $bot->quantity += $n;
        }
        if ($bot->medicine[1] != 0)
        {
            $n = $bot->medicine[1];
            $medicineMsg .= "Paracetamol: $n\n";
            $bot->quantity += $n;
        }
        if ($bot->medicine[2] != 0)
        {
            $n = $bot->medicine[2];
            $medicineMsg .= "Betadine: $n\n";
            $bot->quantity += $n;
        }
        if ($bot->medicine[3] != 0)
        {
            $n = $bot->medicine[3];
            $medicineMsg .= "Whisper: $n\n";
            $bot->quantity += $n;
        }
        if ($bot->medicine[4] != 0)
        {
            $n = $bot->medicine[4];
            $medicineMsg .= "Vicks: $n\n";
            $bot->quantity += $n;
        }
    }

    $bot->sendMessage($foodMsg);
    $bot->sendMessage($houseMsg);
    $bot->sendMessage($medicineMsg);

    $bot->answerCallbackQuery([
        'text' => 'Checkout receipt generated!'
    ]);

    $bot->totalPrice = $bot->quantity * 5;
    $finalMsg = "A total of $bot->quantity items is in your cart.\n\nTotal amount payable: $bot->totalPrice Rupees.";
    $finalMsg .= "\n\n\nWe are sending you a QR Code to pay for the same.\n\nHappy shopping!";

    $bot->sendMessage($finalMsg);

    $imagePath = 'image.png';
    $imageFile = new InputFile($imagePath);

    $caption = "Total amount: $bot->totalPrice \n\n";
    $caption .= "Bill named to: $bot->name\n\n";
    $caption .= "To be delivered on: $bot->deliveryAddress";

    $bot->sendPhoto($imageFile, ['caption' => $caption]);
});



/*
** Let's run the bot now!
** Let this bot listen to your needs!
*/
$bot->run();
?>