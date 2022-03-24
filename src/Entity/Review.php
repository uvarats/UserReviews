<?php

namespace App\Entity;

use App\Repository\ReviewRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReviewRepository::class)]
class Review
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'float')]
    private $rating;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\Column(type: 'string', length: 20)]
    private $reviewGroup;

    #[ORM\Column(type: 'text')]
    private $text;

    #[ORM\Column(type: 'datetime')]
    private $creationTime;

    #[ORM\Column(type: 'array')]
    private $likes = [];

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'reviews')]
    #[ORM\JoinColumn(nullable: false)]
    private $author;

    #[ORM\ManyToMany(targetEntity: Tag::class, inversedBy: 'reviews', cascade: ['persist'])]
    private $tags;

    #[ORM\OneToMany(mappedBy: 'Review', targetEntity: Comment::class)]
    private $comments;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRating(): ?float
    {
        return $this->rating;
    }

    public function setRating(float $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getReviewGroup(): ?string
    {
        return $this->reviewGroup;
    }

    public function setReviewGroup(string $reviewGroup): self
    {
        $this->reviewGroup = $reviewGroup;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }


    public function getCreationTime(): ?\DateTimeInterface
    {
        return $this->creationTime;
    }

    public function setCreationTime(\DateTimeInterface $creationTime): self
    {
        $this->creationTime = $creationTime;

        return $this;
    }

    public function __toString(): string
    {
        return '';
    }

    public function getLikes(): ?array
    {
        return $this->likes;
    }

    public function setLikes(array $likes): self
    {
        $this->likes = $likes;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        $this->tags->removeElement($tag);

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setReview($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getReview() === $this) {
                $comment->setReview(null);
            }
        }

        return $this;
    }

}
