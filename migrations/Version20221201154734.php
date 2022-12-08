<?php
declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Class Version20221201154734
 * @package DoctrineMigrations
 */
final class Version20221201154734 extends AbstractMigration
{
    /**
     * @return string
     */
    public function getDescription(): string
    {
        return '';
    }

    /**
     * @param Schema $schema
     * @return void
     */
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE girl (id INT AUTO_INCREMENT NOT NULL, created_by_id INT NOT NULL, created_at INT DEFAULT 0 NOT NULL, name VARCHAR(255) NOT NULL, rating INT DEFAULT 1000 NOT NULL, votes INT DEFAULT 0 NOT NULL, updated_at INT DEFAULT 0 NOT NULL, INDEX IDX_99BED60EB03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE girl ADD CONSTRAINT FK_99BED60EB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
    }

    /**
     * @param Schema $schema
     * @return void
     */
    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE girl DROP FOREIGN KEY FK_99BED60EB03A8386');
        $this->addSql('DROP TABLE girl');
    }
}
