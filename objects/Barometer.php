<?php

class Barometer
{

    public function __construct($db)
    {
        $this->conn = $db;
    }

    /*
     * Retourne le baromètre de tout les stores
     */

    public function getBarometer()
    {
        $query = "  SELECT  b.appURL,b.appIdAllStore, a.appID, a.appName, DATE_FORMAT(c.DateMeasure, '%d/%m/%Y') as DateMeasure, appCurrentStars, a.appID, c.Unites_cumul, c.Unites_total, b.icon, b.storeicon, a.appVersion, a.currentVersionReleaseDate, e.Unites
                    FROM appstore a
                    INNER JOIN apps_infos b ON (b.appID=a.appID)
                    inner JOIN sales_cumul c ON (a.appID=c.appID)
                    inner join (select appID,max(DateMeasure) as maxdate from sales_cumul group by appID) d
                    on c.appID=d.appID and c.DateMeasure=d.maxdate
                    left JOIN appstore_uvisitor e ON (a.appID=e.appID and e.DateMeasure = (select max(DateMeasure) from appstore_uvisitor))
                    ORDER BY appCurrentStars DESC
            ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    /*
     * Retourne le baromètre de l'App Store
     */

    public function getIosBarometer()
    {
        $query = "  SELECT  b.appURL,b.appIdAllStore, a.appID, a.appName, DATE_FORMAT(c.DateMeasure, '%d/%m/%Y') as DateMeasure, appCurrentStars, a.appID, c.Unites_cumul, c.Unites_total, b.icon, b.storeicon, a.appVersion, a.currentVersionReleaseDate, e.Unites
                   FROM appstore a
                   INNER JOIN apps_infos b ON (b.appID=a.appID)
                   inner JOIN sales_cumul c ON (a.appID=c.appID)
                   inner join (select appID,max(DateMeasure) as maxdate from sales_cumul group by appID) d
                   on c.appID=d.appID and c.DateMeasure=d.maxdate
                   left JOIN appstore_uvisitor e ON (a.appID=e.appID and e.DateMeasure = (select max(DateMeasure) from appstore_uvisitor))
                   WHERE a.appID
                   NOT LIKE '00%'
                   ORDER BY appCurrentStars DESC
            ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    
    /*
     * Retourne le baromètre des App Beta
     */

    public function getBetaBarometer()
    {
        $query = "  SELECT  b.appURL,b.appIdAllStore, a.appID, a.appName, DATE_FORMAT(c.DateMeasure, '%d/%m/%Y') as DateMeasure, appCurrentStars, a.appID, c.Unites_cumul, c.Unites_total, b.icon, b.storeicon, a.appVersion, a.currentVersionReleaseDate, e.Unites
                   FROM appstore a
                   INNER JOIN apps_infos b ON (b.appID=a.appID) AND beta=1
                   inner JOIN sales_cumul c ON (a.appID=c.appID)
                   inner join (select appID,max(DateMeasure) as maxdate from sales_cumul group by appID) d
                   on c.appID=d.appID and c.DateMeasure=d.maxdate
                   left JOIN appstore_uvisitor e ON (a.appID=e.appID and e.DateMeasure = (select max(DateMeasure) from appstore_uvisitor))
                   ORDER BY appCurrentStars DESC
            ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    /*
     * Retourne le baromètre de Google Play
     */

    public function getAndroidBarometer()
    {
        $query = "  SELECT  b.appURL,b.appIdAllStore, a.appID, a.appName, DATE_FORMAT(c.DateMeasure, '%d/%m/%Y') as DateMeasure, appCurrentStars, a.appID, c.Unites_cumul, c.Unites_total, b.icon, b.storeicon, a.appVersion, a.currentVersionReleaseDate, e.Unites
                    FROM appstore a
                    INNER JOIN apps_infos b ON (b.appID=a.appID)
                    inner JOIN sales_cumul c ON (a.appID=c.appID)
                    inner join (select appID,max(DateMeasure) as maxdate from sales_cumul group by appID) d
                    on c.appID=d.appID and c.DateMeasure=d.maxdate
                    left JOIN appstore_uvisitor e ON (a.appID=e.appID and e.DateMeasure = (select max(DateMeasure) from appstore_uvisitor))
                    WHERE a.appID
                    LIKE '00%'
                    ORDER BY appCurrentStars DESC
            ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

}
