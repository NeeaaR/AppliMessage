<?php

namespace App\Entity;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length = 20)
     */
    private $role = 'ROLE_USER';

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $photo;
    private $file;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Groupe", inversedBy="users")
     */
    private $groupes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Message", mappedBy="user")
     */
    private $messages;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Groupe", mappedBy="users_p")
     */
    private $groupe_p;

    public function __construct()
    {
        $this->groupes = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->groupe_p = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        return [$this -> role];

        return array_unique($roles);
    }

    public function getRole(){
        return $this -> role;
    }

    public function setRoles(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getFile() {
        return $this->file;
    }

    public function setFile(UploadedFile $file) {
        $this->file = $file;
        return $this;
    }

    public function fileUpload() {
        $newName = $this ->renameFile($this ->file -> getClientOriginalName());
        $this->photo = $newName;
        $this->file->move(__DIR__ . '/../../public/photos/', $newName);
    }

    public function renameFile($nom) {
        return 'photo' . time() . '_' . rand(1, 999) . '_' . $nom;
    }

    public function removeFile() {
        if(file_exists(__DIR__ . '/../../public/photos/' . $this->photo) && $this -> photo!= 'default.jpg') {
            unlink(__DIR__ . '/../../public/photos/' . $this->photo);
        }
    }

    /**
     * @return Collection|Groupe[]
     */
    public function getGroupes(): Collection
    {
        return $this->groupes;
    }

    public function addGroupe(Groupe $groupe): self
    {
        if (!$this->groupes->contains($groupe)) {
            $this->groupes[] = $groupe;
        }

        return $this;
    }

    public function removeGroupe(Groupe $groupe): self
    {
        if ($this->groupes->contains($groupe)) {
            $this->groupes->removeElement($groupe);
        }

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setUser($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->contains($message)) {
            $this->messages->removeElement($message);
            // set the owning side to null (unless already changed)
            if ($message->getUser() === $this) {
                $message->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Groupe[]
     */
    public function getGroupeP(): Collection
    {
        return $this->groupe_p;
    }

    public function addGroupeP(Groupe $groupeP): self
    {
        if (!$this->groupe_p->contains($groupeP)) {
            $this->groupe_p[] = $groupeP;
            $groupeP->setUsersP($this);
        }

        return $this;
    }

    public function removeGroupeP(Groupe $groupeP): self
    {
        if ($this->groupe_p->contains($groupeP)) {
            $this->groupe_p->removeElement($groupeP);
            // set the owning side to null (unless already changed)
            if ($groupeP->getUsersP() === $this) {
                $groupeP->setUsersP(null);
            }
        }

        return $this;
    }
}
