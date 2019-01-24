# Parties
Parties plugin for PocketMine-MP

## Using Parties API

### Getting the instance of the plugin

```php
use Parties\Parties;

$instance = Parties::getInstance();
```

### Using events API

```php
use Parties\event\party\PartyCreateEvent;

public function onCreate(PartyCreateEvent $event) {
    $party = $event->getParty();
}
```

You can see the full event list [here](https://github.com/Diduhless/Parties/tree/master/src/Parties/event)

### Getting the session of a player

```php
$session = $instance-getSessionManager()->>getSession(Player);
```

### Getting the player instance of a session
```php
$player = $session->getOwner();
```

### Getting the party of a player
```php
if($session->hasParty()) {
    $party = $session->getParty();
}
```

### Making all the members of a party do something
```php
foreach($party->getMembers() as $member) {
    $member->function();
}
```




You can see the full API in the [Session](https://github.com/Diduhless/Parties/blob/master/src/Parties/session/Session.php) and the [Party](https://github.com/Diduhless/Parties/blob/master/src/Parties/party/Party.php) classes.
