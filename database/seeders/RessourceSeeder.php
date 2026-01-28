<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ressource;
use App\Models\CategorieRessource;
use Illuminate\Support\Arr;

class RessourceSeeder extends Seeder
{
    public function run(): void
    {
        // Données avec descriptions uniques pour chaque ressource
        $data = [
            'Serveurs physiques' => [
                ['nom' => 'Serveur de calcul', 'description' => 'Serveur dédié aux calculs intensifs et simulations.'],
                ['nom' => 'Serveur de base de données', 'description' => 'Serveur optimisé pour héberger des bases de données critiques.'],
                ['nom' => 'Serveur applicatif', 'description' => 'Serveur pour déployer et exécuter des applications métiers.'],
                ['nom' => 'Serveur de virtualisation', 'description' => 'Serveur utilisé pour créer et gérer plusieurs machines virtuelles.'],
                ['nom' => 'Serveur de sauvegarde', 'description' => 'Serveur dédié aux sauvegardes régulières des données importantes.'],
                ['nom' => 'Serveur GPU / calcul intensif', 'description' => 'Serveur équipé de GPU pour le calcul scientifique et le machine learning.'],
            ],
            'Machines virtuelles' => [
                ['nom' => 'VM Linux', 'description' => 'Machine virtuelle sous Linux pour tests et développement.'],
                ['nom' => 'VM Windows', 'description' => 'Machine virtuelle sous Windows pour compatibilité applicative.'],
                ['nom' => 'VM de test / développement', 'description' => 'VM utilisée uniquement pour tests et développement.'],
                ['nom' => 'VM de production', 'description' => 'VM stable et sécurisée pour le déploiement en production.'],
                ['nom' => 'VM haute disponibilité', 'description' => 'VM configurée pour disponibilité maximale et tolérance aux pannes.'],
                ['nom' => 'VM à ressources dédiées', 'description' => 'VM avec ressources CPU et RAM réservées pour les charges critiques.'],
            ],
            'Stockage' => [
                ['nom' => 'Baie de stockage SAN', 'description' => 'Stockage centralisé performant pour les serveurs.'],
                ['nom' => 'Stockage NAS', 'description' => 'Stockage réseau accessible par plusieurs utilisateurs simultanément.'],
                ['nom' => 'Stockage objet', 'description' => 'Stockage flexible pour les fichiers volumineux et non structurés.'],
                ['nom' => 'Disque virtuel', 'description' => 'Disque alloué aux machines virtuelles.'],
                ['nom' => 'Volume de sauvegarde', 'description' => 'Volume dédié aux sauvegardes régulières.'],
                ['nom' => 'Stockage SSD / HDD', 'description' => 'Stockage rapide SSD ou traditionnel HDD selon besoin.'],
            ],
            'Équipements réseau' => [
                ['nom' => 'Routeur', 'description' => 'Dispositif pour diriger le trafic réseau entre différentes sous-réseaux.'],
                ['nom' => 'Switch', 'description' => 'Permet de connecter plusieurs appareils sur un réseau local.'],
                ['nom' => 'Pare-feu', 'description' => 'Sécurise le réseau contre les accès non autorisés.'],
                ['nom' => 'Load Balancer', 'description' => 'Répartit la charge réseau ou applicative entre plusieurs serveurs.'],
                ['nom' => 'Point d’accès réseau', 'description' => 'Fournit l’accès Wi-Fi aux utilisateurs.'],
                ['nom' => 'Équipement VPN', 'description' => 'Permet des connexions sécurisées à distance au réseau interne.'],
            ],
            'Réseau virtuel' => [
                ['nom' => 'VLAN', 'description' => 'Réseau local virtuel pour segmenter le trafic.'],
                ['nom' => 'Sous-réseau virtuel', 'description' => 'Segment virtuel d’un réseau pour organisation et sécurité.'],
                ['nom' => 'Tunnel VPN', 'description' => 'Canal sécurisé pour transmettre des données sur Internet.'],
                ['nom' => 'Réseau privé virtuel (VPC)', 'description' => 'Réseau isolé pour déployer des ressources cloud sécurisées.'],
            ],
            'Sécurité' => [
                ['nom' => 'Pare-feu virtuel', 'description' => 'Filtrage du trafic réseau pour les environnements virtuels.'],
                ['nom' => 'IDS / IPS', 'description' => 'Système de détection et de prévention d’intrusions réseau.'],
                ['nom' => 'Certificat SSL', 'description' => 'Permet des communications sécurisées via HTTPS.'],
                ['nom' => 'Passerelle de sécurité', 'description' => 'Contrôle et sécurise le trafic entre différents réseaux.'],
            ]
        ];

        // Création des ressources
        foreach ($data as $categorieNom => $ressources) {
            $categorie = CategorieRessource::where('nom', $categorieNom)->first();
            if (!$categorie) continue;

            foreach ($ressources as $ressourceData) {
                Ressource::create([
                    'nom' => $ressourceData['nom'],
                    'img' => Arr::random(['ress.png', 'ress2.png','ress3.png']),
                    'description' => $ressourceData['description'],
                    'cpu' => '4 vCPU',
                    'ram' => '8 Go',
                    'capacite_stockage' => '500 Go',
                    'bande_passante' => '1 Gbps',
                    'os' => 'Linux',
                    'localisation' => 'Block1',
                    'statut' => 'disponible',
                    'id_categorie' => $categorie->id_categorie
                ]);
            }
        }
    }
}
