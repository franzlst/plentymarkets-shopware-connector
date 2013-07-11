![plentymarkets Logo](http://www.plentymarkets.eu/layout/pm/images/logo/plentymarkets-logo.jpg)
# plentymarkets shopware connector

- **License**: AGPL v3
- **Github Repository**: <https://github.com/plentymarkets/plentymarkets-shopware-connector>

## Einleitung

Dieses shopware-Plugin ermöglicht die optimale Verbindung zwischen shopware und plentymarkets. plentymarkets übernimmt dabei die führende Rolle in der Haltung der gesamten Daten des jeweiligen Nutzers.

Für Kunden, die aktuell bereits shopware einsetzen, ist die Überführung der Daten hin zu plentymarkets recht einfach möglich, da hierzu ein eigener Prozess in das Plugin integriert wurde. Damit ist eine vollständige Datenübernahme, u.a. der Artikelstammdaten, Aufträge oder Kundendaten, von shopware zu plentymarkets möglich. Nach der Übertragung erfolgt die weitere Bearbeitung und Pflege dieser Daten in plentymarkets.

Damit die Daten in beiden Systemen synchron gehalten werden, nimmt das Plugin in bestimmten zeitlichen Intervallen einen Datenaustausch zwischen den beiden Systemen vor. Hierbei werden beispielsweise neue Aufträge von shopware an plentymarkets übertragen oder Warenbestandsänderungen von plentymarkets an shopware gemeldet, sofern es bei einem der angeschlossenen Marktplätze zu einem Verkauf und somit zu einer Warenbestandsänderung kam.

Das Plugin wird innerhalb von shopware installiert. Der Datenaustausch zwischen dem Plugin und plentymarkets erfolgt über die plentymarkets SOAP API. Ein Wechsel der plentymarkets-Version ist somit unabhängig von einem Plugin-Update möglich, sofern die neue plentymarkets-Version die SOAP API-Version des installierten Plugins noch unterstützt. Im Regelfall werden SOAP-Versionen von plentymarkets für 12 Monate gewartet. Nach Ablauf dieser Zeit muss somit ein Wechsel auf die aktuelle Plugin-Version erfolgen. Nach aktueller Planung wird das Plugin dauerhaft kostenlos angeboten werden.

## Weiterentwicklung & Pflege

Für Anwender, die spezielle Funktionen vermissen, ist der vollständige Programmcode offen über github verfügbar. Um eigene Erweiterungen vorzunehmen, muss ein Fork unseres Repositories erstellt werden.

Des Weiteren besteht die Möglichkeit, Vorschläge für allgemeingültige Erweiterungen oder Korrekturen per commit an plentymarkets zu senden. Wir prüfen diese und übernehmen die Änderungsempfehlungen nach einer erfolgreichen Prüfung.

Eine dauerhafte Wartung des Plugins auf zukünftige plentymarkets SOAP API-Versionen wird nach deren Freigabe zeitnah durch unser Entwicklerteam vorgenommen. Individuelle Erweiterungen dieses Plugins können wir im Rahmen von Enterprise-Projekten vornehmen. In allen anderen Fällen sind individuelle Erweiterungen auch über die von uns empfohlenen shopware-Agenturen möglich.

## Installation
### Systemvoraussetzungen
Für das Plugin sind mindestens **plentymarkets 5.0** und **shopware 4.1** nötig. Innerhalb von shopware muss das **Cron** Plugin installiert und aktiviert sein. Das Cron Plugin ist es­sen­zi­ell für den Datenaustausch.

Im shopware-System muss mindestens ein Hersteller vorhanden sein.

### Installation via github
#### Herunterladen als Archiv
Der Quellcode kann direkt und ohne Anmeldung als zip-Archiv von github heruntergeladen werden. Hierfür ist auf Ihrem Server keine Installtion von git erforderlich. Das Archiv muss anschließend in das Verzeichnis
	
	/Pfad/zu/shopware/engine/shopware/Plugins/Local/Backend/Plentymarkets
	
extrahiert werden. Folgende Verzeichnisstruktur sollte danach vorhanden sein:

	+-shopware/
  		+-engine/
    		+-shopware/
      			+-Plugins/
        			+-Local/
          				+-Backend/
            				+-Plentymarkets/
              					+-Components/
              					+-Controllers/
              					+-Views/

Anschließend kann das Plugin über den Plugin Manager installiert werden. Der Nachteil an dieser Variante ist, dass der Prozess immer wiederholt werden muss, sobald es eine Änderung am Plugin gegeben hat.

#### Klonen des Repositories
Eine komfortablere Variante ist das Klonen des Repositorys mittels git. Hierfür muss git auf dem entsprechenden Server installiert sein. Weitere Informationen dazu finden sie auf [der Hompage](http://git-scm.com/) von git.

Das Repository muss in folgendes Verzeichnis geklont werden:

	/Pfad/zu/shopware/engine/Shopware/Plugins/Local/Backend
	
Gehen Sie dazu wie folgt vor:

	cd /Pfad/zu/shopware/engine/Shopware/Plugins/Local/Backend
	git clone https://github.com/plentymarkets/plentymarkets-shopware-connector.git Plentymarkets
	
Das Klonen beginnt:

	Cloning into 'Plentymarkets'...
	remote: Counting objects: 853, done.
	remote: Compressing objects: 100% (413/413), done.
	remote: Total 853 (delta 430), reused 853 (delta 430)
	Receiving objects: 100% (853/853), 200.64 KiB | 302 KiB/s, done.
	Resolving deltas: 100% (430/430), done.
	
Anschließend kann das Plugin über den Plugin Manager installiert werden. Daraufhin ist der aktuelle Stand des Plugins installiert. Um später eine Aktualisierung durchzuführen, muss in dem entsprechenden Verzeichnis der folgende Befehl ausgeführt werden:

	cd /Pfad/zu/shopware/engine/Shopware/Plugins/Local/Backend/Plentymarkets
	git pull origin master
	
Das Plugin muss jetzt ggf. über den Plugin Manager aktualisiert werden.

### Installation aus dem Community Store
Nach der Pilotphase kann das Plugin auch aus dem shopware Community Store installiert werden.

## Einrichtung
### plentymarkets
Folgende Schritte müssen *vor* der Einrichtung des Plugins innerhalb des plentymarkets-Systems ausgeführt werden.

#### Mandant anlegen
JG

#### Benutzer anlegen
Legen Sie unter **Einstellungen » Grundeinstellungen » Benutzer » Konten** einen neuen Benutzer an. Dieser Benutzer wird für die Kommunikation zwischen plentymarkets und shopware über die SOAP API verwendet. Nutzen Sie für den Benutzer deshlab am besten die Typ-Bezeichnung **API**.

Folgende Calls werden vom Plugin genutzt:

* AddCustomerDeliveryAddresses
* AddCustomers
* AddIncomingPayments
* AddItemAttribute
* AddItemAttributeValueSets
* AddItemCategory
* AddItemsBase
* AddItemsImage
* AddLinkedItems
* AddOrders
* AddProperty
* AddPropertyGroup
* AddPropertyToItem
* GetAttributeValueSets
* GetCurrentStocks
* GetItemAttributes
* GetItemCategoryCatalogBase
* GetItemsBase
* GetItemsImages
* GetItemsPriceUpdate
* GetLinkedItems
* GetMethodOfPayments
* GetMultiShops
* GetOrderStatusList
* GetPlentyMarketsVersion
* GetProducers
* GetPropertiesList
* GetSalesOrderReferrer
* GetShippingProfiles
* GetShippingServiceProvider
* GetVATConfig
* GetWarehouseList
* SearchOrders
* SetAttributeValueSetsDetails
* SetProducers

Wenn die Berechtigungen manuell vergeben werden, muss sichergestellt sein, dass der Benutzer **alle** o. g. Calls ausführen darf. Ansonsten kann es sowohl im shopware- als auch im plentymarkets System zu unerwartetem Verhalten kommen.

#### Auftragsherkunft (optional)
Soll den von shopware zu plentymarkets exportierten Aufträgen eine individuelle Herkunft zugeordnet werden, muss diese zuvor in plentymarkets unter **Einstellungen » Aufträge » Auftragsherkunft** angelegt werden.

#### Freitextfelder (optional)
Um die Freitextfelder/Attribute der Artikel aus shopware zu übernehmen, müssen diese in plentymarkets unter **Einstellungen » Artikel » Freitextfelder** definiert werden.

### shopware
Nach der Installation und Aktivierung des Plugins über den Plugin Manager muss der Shop-Cache geleert und das shopware-Fenster neu geladen werden, damit der Menüpunkt **Einstellungen » plentymarkets** erscheint.

**Wichtig:** Damit das Plugin ordnungsgemäß arbeiten kann, müssen die folgenden Schritte genau eingehalten werden.

#### Verbindung zu plentymarkets herstellen
Geben Sie unter dem Menüpunkt **API** die URL Ihres System sowie die Zugangsdaten des Benutzers ein, mit dem die Kommunikation stattfinden soll. Sie können die Verbindung prüfen, im dem Sie auf den entsprechenden Button *Zugangsdaten testen* klicken.

**Achtung:** Das Speichern impliziert ein Testen der Zugangsdaten. Sowohl die Funktion *Zugangsdaten testen* als auch das Speichern führen dazu, dass der SOAP Call GetAuthentificationToken aufgerufen wird. Dieser Call ist auf 30 Anfrgen pro Tag und Benutzer limitiert! Werden diese Funktionen wiederholt genutzt, kann es sein, dass das Abrufen des Tokens bis zum Ende des Tages gesperrt wird.

Nach dem Speichern von korrekten Daten, werden die weiteren Reiter **Einstellungen**, **Mapping** und **Datenaustausch** aktiviert. Sobald keine Verbindung zu plentymarkets hergestellt werden kann, werden diese Reiter und jegliche Kommunikation automatisch deaktiviert!

#### Einstellungen
Bitte nehmen sie hier wichtige Einstellungen vor, ohne die der Datenaustausch nicht stattfinden kann. Diese Einstellungen *müssen* gemacht werden.

Die Daten, die von plentymarkets geladen werden, werden für einen Tag gespeichert.

 * Mandant (Zuordnung zu Ihrem shopware-System innerhalb von plentymarkets)
 * Lager (Datenquelle für den Warenbestandsabgleich)
 * Hersteller (In shopware erforderlich, in plentymarkets optional)
 * Herkunft (Zuordnung der Aufträge zu einer Herkunft)
 * Status: bezahlt (shopware Status der signalisiert, dass der Auftrag komplett bezahlt ist. Löst das Buchen des Zahlungseinganges bei plentymarkets aus)
 * Status: versendet (shopware Status der gesetzt wird, wenn bei einem Auftag innerhalb von plentymarkets der Warenausgang gebucht wird)
 * Warenausgang (Definiert die Bedingung mit der "versendete" Aufträge aus plentymarkets abgerufen werden)
 * Warenausgang (Intervall der Auftragssynchronisation)


#### Mapping
Für alle Daten, die nicht automatisch gemappt werden können, müssen Sie die Verknüpfung manuel herstellen.

**Wichtig:** Es müssen alle Datensätze gemappt werden! Jedem Datensatz von shopware kann genau genau ein Datensatz von plentymarktes zugeordner werden und andersrum (1:1 Verknüpfung). D.h. eine Mehrfachverwendung ist nicht möglich.

Wenn das Mapping nicht vollständig abgeschlossen ist oder im laufenden Betrieb weitere Datensätze innerhalb von shopware erstellt werden, die manuell gemappt werden müssen, wird der gesamte Datenaustausch deaktiviert. Auf der Startseite des Plugins werden Sie auf diesen Umstand hingewiesen.

##### Währungen
Die Währungen werden Aufträgen zugeordnet, die von shopware zu plentymarkets exportiert werden. Da Aufträge nicht von plentymarkets zu shopware exportiert werden, ist es nicht erforderlich, alle Währungen von plentymarkets auch in Ihrem shopware System anzulegen.

##### Einheiten
Die Einheiten sind den Artikeln zugeordnet. Da der Abgleich in beide Richtungen stattfindet, macht es Sinn, allen Einheiten, die sie bei plentymarkets verwenden auch bei shopware anzulegen. Wenn eine Einheit innerhalb von shopware nicht zugeordnet werden kann, wird dem Artikel keine Einheit definiert.

##### Zahlungsarten
Die Zahlungsarten werden den Aufträgen zugeordnet.

**Wichtig:** Alle in plentymarkets mit einer Zahlungsart verbundenen Aktionen werden ausgeführt! D.h. wenn Sie als Zahlungsart Rechnung auswählen, wird der Auftrag nach dem Import in plentymarkets automatisch in den Status 4 bzw 5 gesetzt. Ebenso werden alle Events die ggf. konfiguriert sind ausgeführt.

**Achtung:** Die Zahlungsart *Vorkasse* kann derzeit nicht als eigenständige Zahlung in plentymarkets verwendet werden. Dieser werden über *Überweisung*, *HBCI* und *EBICS* abgedeckt.

##### Steuersätze
… sind zu hoch.

##### Versandarten
Das Mapping der Versandarten dient dazu, um die volle Funktionalität von plentymarkets' Fullfilment nutzen zu können. … 

##### Länder
Die Länder werden den Anschriften (Rechnung- / Liefer-) zugeordnet. Kundendaten werden nur zu plentymarkets exportiert. Von daher ist es nicht erforderlich, alle plentymarkets-Länder in Ihrem shopware System anzulegen.

## Datenaustausch
Das Mapping für die Daten aus der Synchronisierung wird automatisch vorgenommen. 

**Achtung:** Der Datenaustauch darf (bzw. kann) nur gestartet weden, wenn alle unter Einrichtung genannten Punkte abgeschlossen sein! Sobald einer der erforderlichen Schritte nur (noch) teilweise abgeschlossen ist, wird der Datenaustausch automatisch deaktiviert. Sofern alle Probleme behoben sind, wird der Datenaustausch wieder aktiviert.

Wenn der Datenaustausch von Ihnen manuell dekativiert worden ist, wird der niemals automatisch aktiviert.

### Initialer Export zu plentymarkets
Alle Artikeldaten aus Ihrem shopware System müssen zu plentymarkets exportiert werden.

### Synchronisation
#### Artikelstammdaten
Die Artikelstammdaten werden stündlich abgeglichen. Der Abgleich beinhaltet folgende Bereiche:


#### Varianten
Varianten können keine Merkmale haben!

#### Preise
Plentymarkets erlaubt nur eine Preisstaffelung bis zu eine Tiefe von 6 Staffelungen.

 * erstes PriceSet
 * nur für Shopkunden mit INdex EK

#### Warenbestände
Die Warenbestände werden alle 15 Minuten inkrementell abgeglichen. Alle Warenbestände von Stammartikeln und Varianten, die sich seit dem letzten Abgleich geändert haben, werden abgerufen und im shopware System angepasst.

Sie können entweder ein bestimmes Lager als Quelle für die Warenbestände festlegen, oder das *virtuelle Gesamtlager* verwenden. Die Artikel in shopware erhalten dann den kumulierten Warenbestand  aus allen vorhandenen Lagern.

In shopware wird immer der netto-Warenbestand von plentymarktes gebucht.

#### Aufträge
Aufträge, die in shopware erstellt werden, werden alle 15 Minuten inkl. der Kundendaten nach plentymarkets exportiert. Die Versandkosten werden übernommen.

Wenn der Warenausgang in plentymarkets bebucht wird, wird der Auftag in shopware in den von Ihnren eingestellten Status gesetzt. Sie können entweder einstellen, dass der Auftrag in plentymarkets einen bestimmen Status erreichen muss, um als Abgeschlossen zu gelten, oder ob es reicht, wenn der Warenausgang gebucht worden ist. Dieser Vorhang findet jede halbe Stunde statt.

Wenn ein Auftrag in shopware einen bestimmten Zahlungsstatus erreicht, wird in plentymarkets ein Zahlungseingang über den gesamten Rechnungsbetrag gebucht. Die Zahlungsart wird entsprechend der Einstellunge gesetzt. Dieser Vorhang findet stündlich statt.

**Achtung:** In plentymarkets kann ein Kunde nur eine Rechnungsadresse hinterlegen. Wenn ein und der selbe Kunde in shopware einen Auftrag mit unterschiedlichen Rechnungsadressen anlegt, wird er bei plentymarkets als neuer Kunde mit eigener ID geführt.

## Log
Im Log erhalten Sie Auskunft über den Datentransfer. Jeder getätigte SOAP Call wird hier inkl. dessen Status (erfolgreich oder nicht) aufgeführt. Darüber hinaus erhalten Sie informationen, wenn bestimmte Datensätze angelegt worden sind oder bei der Verarbeitung Fehler aufgetreten sind. Es Wird generell zwischen Meldung und Fehler entschieden.

Jede Meldung (und jeder Fehler) hat ein bestimmtes Präfix. Dieses kennzeichnet grob, welcher Prozess verantworlich ist. ES gibt die folgenden Präfixe:

* Export:Initial:<Entität>
* Export:Item
* Export:Item:Image
* Export:Item:Variant
* Export:Order
* Soap:Auth
* Soap:Call
* Sync:Item
* Sync:Item:Image
* Sync:Item:Linked
* Sync:Item:Price
* Sync:Item:Stock
* Sync:Item:Variant
* Sync:Order:OutgoingItems
* Sync:Order:Payment

### Meldungen
Bei den Meldungen handelt es sich um Informationen und Positive Rückmeldungen.

### Fehler
* unerwaretet Fehler
* 
