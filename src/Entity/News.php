<?php

namespace App\Entity;

use App\Repository\NewsRepository;
use Cocur\Slugify\Slugify;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NewsRepository::class)]
#[ORM\Table(name: "News")]
class News
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $author = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $recordingDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $lastUpdateDate = null;

    #[ORM\OneToMany(mappedBy: 'news', targetEntity: Comment::class)]
    private Collection $comments;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;
        
        return $this;
    }
    
    public function getSubContent(int $ln = 100) : ?string {
        return substr($this->content, 0, $ln)."...";
    }

    public function getRecordingDate(): ?\DateTimeInterface
    {
        return $this->recordingDate;
    }

    public function getFormatedRecordingDate() : string{
        if($this->recordingDate != null) {
            return $this->recordingDate->format('d/m/Y \à H\h:i');
        }
        return '';
    }

    public function setRecordingDate(\DateTimeInterface $recordingDate): self
    {
        $this->recordingDate = $recordingDate;

        return $this;
    }

    public function getLastUpdateDate(): ?\DateTimeInterface
    {
        return $this->lastUpdateDate;
    }

    public function setLastUpdateDate(?\DateTimeInterface $lastUpdateDate): self
    {
        $this->lastUpdateDate = $lastUpdateDate;

        return $this;
    }

    /**
     * return formate date for last update, as string
     *
     * @return string
     */
    public function getFormatedLastUpdateDate () : string {
        if($this->lastUpdateDate  != null) {
            return $this->lastUpdateDate->format('d/m/Y \à H\h:i');
        }
        return '-';
    }

    /**
     * renvoie le slug du news
     *
     * @return string
     */
    public function getSlug () : string {
        return (new Slugify())->slugify($this->title);
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
            $this->comments->add($comment);
            $comment->setNews($this);
        }

        return $this;
    }

    /**
     * check when news has comments
     *
     * @return boolean
     */
    public function hasComments() : bool {
        return $this->comments !== null && !$this->comments->isEmpty();
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getNews() === $this) {
                $comment->setNews(null);
            }
        }

        return $this;
    }
}
