<?php

namespace IproSync\Ipro;

class PullPagination
{
    protected int $startPage = 1;
    /**
     * 0 = until end
     * @var int
     */
    protected int $endPage = 0;
    protected int $perPage = 300;

    public function __construct(int $startPage = 1, int $endPage = 0, int $perPage = 300)
    {
        $this->startPage = $startPage;
        $this->endPage   = $endPage;
        $this->perPage   = $perPage;
    }

    public static function page(int $page = 1, int $perPage = 300): static
    {
        return new static($page, $page, $perPage);
    }

    public static function pages(int $startPage = 1, int $endPage = 0, int $perPage = 300): static
    {
        return new static($startPage, $endPage, $perPage);
    }

    public static function allPages(int $perPage = 300): static
    {
        return new static(1, 0, $perPage);
    }

    public function startPage(int $startPage = 1): static
    {
        $this->startPage = $startPage;

        return $this;
    }

    public function endPage(int $endPage = 0): static
    {
        $this->endPage = $endPage;

        return $this;
    }

    public function perPage(int $perPage = 300): static
    {
        $this->perPage = $perPage;

        return $this;
    }

    public function amendQuery(array $query = []): array
    {
        $query['size']  = $this->perPage;
        $query['index'] = $this->startPage;

        return $query;
    }

    public function hasNext(int $total): bool
    {
        if ($this->endPage
            && ($this->endPage) <= ($this->startPage)
        ) {
            return false;
        }

        return ($this->perPage * $this->startPage) < $total;
    }

    public function nextPagination(int $total): ?static
    {
        if ($this->hasNext($total)) {
            return new static($this->startPage + 1, $this->endPage, $this->perPage);
        }

        return null;
    }

    public function getStartPage(): int
    {
        return $this->startPage;
    }

    public function getEndPage(): int
    {
        return $this->endPage;
    }

    public function getPerPage(): int
    {
        return $this->perPage;
    }
}
