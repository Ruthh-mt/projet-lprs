<?php
    Class ModeleEvenement{
        private $idEvenement;
        private $titreEvenement;
        private $typeEvenement;
        private $descEvenement;
        private $lieuEvenement;
        private $elementEvenement;
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
        public function getTitreEvenement()
        {
            return $this->titreEvenement;
        }

        /**
         * @param mixed $titreEvenement
         */
        public function setTitreEvenement($titreEvenement): void
        {
            $this->titreEvenement = $titreEvenement;
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
        public function getDescEvenement()
        {
            return $this->descEvenement;
        }

        /**
         * @param mixed $descEvenement
         */
        public function setDescEvenement($descEvenement): void
        {
            $this->descEvenement = $descEvenement;
        }

        /**
         * @return mixed
         */
        public function getLieuEvenement()
        {
            return $this->lieuEvenement;
        }

        /**
         * @param mixed $lieuEvenement
         */
        public function setLieuEvenement($lieuEvenement): void
        {
            $this->lieuEvenement = $lieuEvenement;
        }

        /**
         * @return mixed
         */
        public function getElementEvenement()
        {
            return $this->elementEvenement;
        }

        /**
         * @param mixed $elementEvenement
         */
        public function setElementEvenement($elementEvenement): void
        {
            $this->elementEvenement = $elementEvenement;
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



    }
