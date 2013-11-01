<?php

/* 
__PocketMine Plugin__ 
name=SAO-MCPE
description=SAO kind of environment in PocketMine server!
version=1.0
author=Junyi00, Glitchmaster_PE, 99leonchang, hexdro
class=SAOMCPE
apiversion=10
*/

class SAOMCPE implements Plugin{
    private $api, $server;
    public function __construct(ServerAPI $api, $server = false){
        $this->api = $api;
        $this->server = ServerAPI::request(); //why do we need this :P
        //Its needed
        //hexdro just contributed :P
    }

    public function init(){
        $this->api->addHandler("player.block.touch", array($this, "preventBreakPlace"), 15); 
        //Getting ready for the player.block.touch -Leon
        
        $this->api->addHandler("player.death", array($this, "BanPlayer"), 15); //Banning is awesome -Junyi00
        
        $this->api->console->register("cash","Having to deal with SAO-MCPE Economy",array($this, "Economy"));
        $this->api->ban->cmdWhitelist("cash");
        
        $this->path = $this->api->plugin->configPath($this);
        
        $this->cash = new Config($this->path . "Economy.yml", CONFIG_YAML, for($i = 1, $i < 1,000,000,000, $i++){
            $i, "User" => "", "Money", "";//this config should do a for loop for each new member that joins -Glitch
        }) //don't understand how the loop will work O.o
        $this->cash = $this->api->plugin->readYAML($this->path . "Economy.yml");//Makes it read YAML :P
        
        $this->DetectSkill = new Config($this->path . "DetectionSkill.yml", CONFIG_YAML, for($i = 1, $i < 1,000,000,000, $i++){
            $i, "User" => "", "SkillLevel", "";//this config should do a for loop for each new member that joins -Glitch
        })
        $this->DetectSkill = $this->api->plugin->readYAML($this->path . "DetectionSkill.yml")
        
        $this->api->schedule(20* 20, array($this, "Healing"), array(), false); //20 secs to heal 1 heatlh
    }
    //Shouldn't we be using storing data using SQL? There are othe stuffs to store too, afraid sing so much yaml would lag the server-Junyi00

    public function __destruct() {}
    
    public function preventBreakPlace($data, $event) {
        switch ($data['type']) {
            case "break": return false; //denied
            case "place": return false; //denied
        }
    }//I like how you guys are adding a lot of commentts, I will do the same -Glitch

    //What type of Economy are we trying to do? I can add my custom sign shop thingy :) -Leon
    //Chest-Sign shop? Maybe others after i think about it -Junyi00
    
    public function BanPlayer($data, $event) {
        $username = $data['player']->username;
        $this->api->ban->ban($username); //bye bye loser? :P
    }
    
    public function Economy($cmd, $args, $issuer){
        $username = $issuer->username;
        $money = $this->cash["Money"];//this is incomplete, I will keep working on it in a bit -Glitch
    }
    
    public function Healing() {
        $players = $this->api->player->online();
        for($i=1;$i<count($players);$i++) {
            $player = $this->api->player->get($players[$i]);
            if ($player->getHealth() != 20) { 
                $player->setHealth($player->getHealth()+1, "Healing"); //heal 1 health
            }
        }
    } //Done? Someone test for me plz :P -Junyi00
    
}
