<?php

namespace App\Entity;

use App\Entity\Adresse;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PersonneRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=PersonneRepository::class)
 * @ApiResource(
 *     attributes={"security"="is_granted(’ROLE_ADMIN’)"}, 
 *     itemOperations={"get", "put", "delete"},
 *     normalizationContext={"groups"={"personne:read"}},
 *     denormalizationContext={"groups"={"personne:write"}}
 * )
 */
class Personne
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"personne:read", "personne:write"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"personne:read", "personne:write"})
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"personne:read", "personne:write"})
     */
    private $prenom;

    /**
     * @ORM\ManyToMany(targetEntity=Adresse::class, inversedBy="personnes", cascade={"remove", "persist"})
     * @Groups({"personne:read", "personne:write"})
     */
    private $adresses;

    /**
     * @ORM\Column(type="datetime")
     * @Groups("personne:read")
     */
    private $dateEnregistrement;

    public function persist($data, array $context = [])
    {
        if (($context['collection_operation_name'] ?? null) === 'post') {
        $dateTimeZone = new \DateTimeZone('Europe/Paris');
        $data->setDateEnregistrement(new \DateTime('now',$dateTimeZone));
    }
$this->entityManager->persist($data);
$this->entityManager->flush();
}
    public function __construct()
    {
        $this->adresses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * @return Collection|Adresse[]
     */
    public function getAdresses(): Collection
    {
        return $this->adresses;
    }

    public function addAdress(Adresse $adress): self
    {
        if (!$this->adresses->contains($adress)) {
            $this->adresses[] = $adress;
        }

        return $this;
    }

    public function removeAdress(Adresse $adress): self
    {
        if ($this->adresses->contains($adress)) {
            $this->adresses->removeElement($adress);
        }

        return $this;
    }
}
