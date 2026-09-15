<?php

namespace App\Dto\Http\Response;

final readonly class PageResponse implements \JsonSerializable
{
    /**
     * @param list<mixed> $content
     */
    public function __construct(
        private array $content,
        private int $pageNumber,
        private int $pageSize,
        private int $totalElements,
        private int $totalPages,
        private bool $isFirst,
        private bool $isLast,
    ) {
    }

    /**
     * @param list<mixed> $content
     */
    public static function from(
        array $content,
        int $pageNumber,
        int $pageSize,
        int $totalElements,
    ): self {
        $totalPages = $pageSize > 0 ? (int) ceil($totalElements / $pageSize) : 0;

        return new self(
            $content,
            $pageNumber,
            $pageSize,
            $totalElements,
            $totalPages,
            $pageNumber === 0,
            $totalPages === 0 || $pageNumber >= $totalPages - 1,
        );
    }

    /**
     * @return array{content: list<mixed>, pageNumber: int, pageSize: int, totalElements: int, totalPages: int, isFirst: bool, isLast: bool}
     */
    public function jsonSerialize(): array
    {
        return [
            'content' => $this->content,
            'pageNumber' => $this->pageNumber,
            'pageSize' => $this->pageSize,
            'totalElements' => $this->totalElements,
            'totalPages' => $this->totalPages,
            'isFirst' => $this->isFirst,
            'isLast' => $this->isLast,
        ];
    }
}
