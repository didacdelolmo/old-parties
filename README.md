# Parties
Parties plugin for PocketMine-MP

## Using Parties API

### Getting the instance of the plugin

```php
use Parties\Parties;

$instance = Parties::getInstance();
```

### Getting the session of a player

```php
$session = $instance->getSession(Player);
```

### Some API guide that might be helpful for you

```php
// Getting the party of a session
if($session->hasParty()) {
    $party = $session->getParty();
}

// Making the members of a party teleport to their leader
$party->teleportToLeader();
```

You can see the full API in the [Session](https://github.com/Diduhless/Parties/blob/master/src/Parties/session/Session.php) and the [Party](https://github.com/Diduhless/Parties/blob/master/src/Parties/party/Party.php) classes.
