<?php

namespace App\Traits;

trait BlogInfoTrait
{
    public function index(): void
    {
        $modelName = $this->getModelName();
        $items = $this->{$modelName}->getAll();

        if (!$items) {
            $this->helpers->view("blog.{$modelName}s");
            return;
        }

        $this->helpers->view("blog.{$modelName}s", ["{$modelName}s" => $items]);
    }

    public function store(array $request): void
    {
        $modelName = $this->getModelName();
        
        if (!$this->checkSecurity($modelName)) {
            return;
        }

        $item = $this->prepareItemData($request);
        $result = $this->{$modelName}->store($item);

        $this->handleResult($result, 'añadir', $modelName, $item['title'] ?? null);
    }

    public function update(int $id, array $request): void
    {
        $modelName = $this->getModelName();
        
        if (!$this->checkSecurity($modelName)) {
            return;
        }

        $item = $this->prepareItemData($request);
        $result = $this->{$modelName}->update($id, $item);

        $this->handleResult($result, 'editar', $modelName, $item['title'] ?? null);
    }

    public function delete(int $id, array $request): void
    {
        $modelName = $this->getModelName();
        
        if (!$this->checkSecurity($modelName)) {
            return;
        }

        $result = $this->{$modelName}->delete($id);

        $this->handleResult($result, 'eliminar', $modelName, $request['title'] ?? null);
    }

    private function getModelName(): string
    {
        $className = (new \ReflectionClass($this))->getShortName();
        return strtolower(str_replace('Controller', '', $className));
    }

    private function checkSecurity(string $modelName): bool
    {
        if (!$this->security->verifyCsrf($request['csrf'] ?? '')) {
            $this->helpers->setPopup('Error de seguridad');
            header("Location: /{$modelName}s");
            return false;
        }

        if (!$this->security->isAdmin()) {
            $this->helpers->setPopup('Solo el admin puede realizar esta operación');
            header("Location: /{$modelName}s");
            return false;
        }

        return true;
    }

    private function prepareItemData(array $request): array
    {
        $item = [
            'title' => $request['name'],
            'url' => $request['url'],
        ];

        if (isset($request['comment'])) {
            $item['comment'] = $request['comment'];
        }

        return $item;
    }

    private function handleResult(bool $result, string $action, string $modelName, ?string $title = null): void
    {
        if (!$result) {
            $this->helpers->setPopup("Error al {$action} el " . $this->getItemName($modelName));
        } else {
            $message = $title ? "{$title} " : "";
            $message .= $this->getActionMessage($action);
            $this->helpers->setPopup($message);
        }

        header("Location: /{$modelName}s");
    }

    private function getItemName(string $modelName): string
    {
        return $modelName === 'link' ? 'link' : 'blog';
    }

    private function getActionMessage(string $action): string
    {
        switch ($action) {
            case 'añadir':
                return 'agregado';
            case 'editar':
                return 'editado';
            case 'eliminar':
                return 'eliminado';
            default:
                return '';
        }
    }
}