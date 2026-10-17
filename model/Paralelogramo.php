<?php
require_once __DIR__ . '/../config/Database.php';

class Paralelogramo
{
    private PDO $pdo;

    public function __construct()
    {
        $database = new Database();
        $this->pdo = $database->conectar();
    }

    public function listar(string $buscar = ''): array
    {
        if ($buscar !== '') {
            $sql = "SELECT * FROM paralelogramo
                    WHERE detalle LIKE :buscarDetalle
                       OR CAST(lado AS CHAR) LIKE :buscarLado
                       OR CAST(base AS CHAR) LIKE :buscarBase
                       OR CAST(altura AS CHAR) LIKE :buscarAltura
                       OR CAST(area AS CHAR) LIKE :buscarArea
                       OR CAST(perimetro AS CHAR) LIKE :buscarPerimetro
                    ORDER BY id_paralelogramo DESC";
            $stmt = $this->pdo->prepare($sql);

            $valorBuscar = '%' . $buscar . '%';

            $stmt->execute([
                'buscarDetalle' => $valorBuscar,
                'buscarLado' => $valorBuscar,
                'buscarBase' => $valorBuscar,
                'buscarAltura' => $valorBuscar,
                'buscarArea' => $valorBuscar,
                'buscarPerimetro' => $valorBuscar,
            ]);

            return $stmt->fetchAll();
        }

        $sql = "SELECT * FROM paralelogramo ORDER BY id_paralelogramo DESC";
        return $this->pdo->query($sql)->fetchAll();
    }

    public function obtenerPorId(int $id): ?array
    {
        $sql = "SELECT * FROM paralelogramo WHERE id_paralelogramo = :id LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        $registro = $stmt->fetch();

        return $registro ?: null;
    }

    public function guardar(string $detalle, float $base, float $lado, float $altura): bool
    {
        $area = $base * $altura;
        $perimetro = 2 * ($base + $lado);

        $sql = "INSERT INTO paralelogramo(detalle, base, lado, altura, area, perimetro)
                VALUES(:detalle, :base, :lado, :altura, :area, :perimetro)";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'detalle' => $detalle,
            'base' => $base,
            'lado' => $lado,
            'altura' => $altura,
            'area' => $area,
            'perimetro' => $perimetro,
        ]);
    }

    public function actualizar(int $id, string $detalle, float $base, float $lado, float $altura): bool
    {
        $area = $base * $altura;
        $perimetro = 2 * ($base + $lado);

        $sql = "UPDATE paralelogramo
                SET detalle = :detalle,
                    base = :base,
                    lado = :lado,
                    altura = :altura,
                    area = :area,
                    perimetro = :perimetro
                WHERE id_paralelogramo = :id";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            'id' => $id,
            'detalle' => $detalle,
            'base' => $base,
            'lado' => $lado,
            'altura' => $altura,
            'area' => $area,
            'perimetro' => $perimetro,
        ]);
    }

    public function eliminar(int $id): bool
    {
        $sql = "DELETE FROM paralelogramo WHERE id_paralelogramo = :id";
        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute(['id' => $id]);
    }
}
