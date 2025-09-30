<?php
    Class Evenement{
        private $idEvenement;
        private $titreEve;
        private $typeEvenement;
        private $descEve;

        private $lieuEve;
        private $elementEve;
        private $nbPlace;


        public function __construct(array $donnees){
            $this->hydrate($donnees);
        }

        private function hydrate(array $donnees){
            foreach ($donnees as $key => $value) {
                $method = 'set'.ucfirst($key);
                if (method_exists($this, $method)) {
                    $this->$method($value);
                }
            }
        }

        /**
         * @return mixed
         */
        public function getIdEvenement()
        {
            return $this->idEvenement;
        }

        /**
         * @param mixed $idEvenement
         */
        public function setIdEvenement($idEvenement): void
        {
            $this->idEvenement = $idEvenement;
        }

        /**
         * @return mixed
         */
        public function getTypeEvenement()
        {
            return $this->typeEvenement;
        }

        /**
         * @param mixed $typeEvenement
         */
        public function setTypeEvenement($typeEvenement): void
        {
            $this->typeEvenement = $typeEvenement;
        }

        /**
         * @return mixed
         */
        public function getLieuEve()
        {
            return $this->lieuEve;
        }

        /**
         * @param mixed $lieuEve
         */
        public function setLieuEve($lieuEve): void
        {
            $this->lieuEve = $lieuEve;
        }

        /**
         * @return mixed
         */
        public function getElementEve()
        {
            return $this->elementEve;
        }

        /**
         * @param mixed $elementEve
         */
        public function setElementEve($elementEve): void
        {
            $this->elementEve = $elementEve;
        }

        /**
         * @return mixed
         */
        public function getNbPlace()
        {
            return $this->nbPlace;
        }

        /**
         * @param mixed $nbPlace
         */
        public function setNbPlace($nbPlace): void
        {
            $this->nbPlace = $nbPlace;
        }

        /**
         * @return mixed
         */
        public function getDescEve()
        {
            return $this->descEve;
        }

        /**
         * @param mixed $descEve
         */
        public function setDescEve($descEve): void
        {
            $this->descEve = $descEve;
        }

        /**
         * @return mixed
         */
        public function getTitreEve()
        {
            return $this->titreEve;
        }

        /**
         * @param mixed $titreEve
         */
        public function setTitreEve($titreEve): void
        {
            $this->titreEve = $titreEve;
        }


    }
