# Integento_Assetsperf

Integento_Assetsperf is a Magento Module adding a simple compression to the CSS/JS merge functionality.

## Features

* JS & CSS : Remove /* comments */ and useless spaces.
* JS & CSS : Insert a script first, or after another one.


## How to insert a script before all

```xml
<action method="addItemFirst">
    <name>custom/custom.js</name>
    <type>js</type>
</action>
```

## How to insert a script after another one

```xml
<action method="addItemAfter">
    <after>prototype/prototype.js</after>
    <name>custom/custom.js</name>
    <type>js</type>
</action>
```

## How to install ?

With modman :

```
modman clone https://github.com/InteGento/Integento_Assetsperf.git
```

## Thanks to :

- @ClaudiuCreanga for his tutorial http://claudiucreanga.me/magento/magento-change-order-css-js.html
